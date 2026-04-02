import { useLoaderData, useActionData } from "react-router";
import type { Route } from "./+types/home";
import { getVisaFiles } from "../lib/api.server";
import { FileSection } from "../components/FileSection";
import { FileList } from "../components/FileList";

const MAX_SIZE_BYTES = 4 * 1024 * 1024;
const ALLOWED_MIMES = ["application/pdf", "image/png", "image/jpeg"];

export function meta({}: Route.MetaArgs) {
  return [{ title: "Visa Dossier" }];
}

export async function loader({ request }: Route.LoaderArgs) {
  const url = new URL(request.url);
  const page = Number(url.searchParams.get("page") ?? 1);

  const [passports, photos, forms] = await Promise.all([
    getVisaFiles("passport", page),
    getVisaFiles("photo", page),
    getVisaFiles("form", page),
  ]);

  return { passports: passports.data, photos: photos.data, forms: forms.data, page };
}

export async function action({ request }: Route.ActionArgs) {
  const formData = await request.formData();
  const intent = formData.get("intent") as string;

  if (intent === "delete") {
    const id = formData.get("id") as string;
    const response = await fetch(`${process.env.BACKEND_URL}/api/visa-files/${id}`, {
      method: "DELETE",
      headers: { Accept: "application/json" },
    });
    if (!response.ok) {
      const { error } = await response.json();
      const message =
        error === "file_not_found"
          ? "Fichier introuvable sur le serveur."
          : error === "visa_file_not_found"
          ? "Fichier introuvable."
          : "Erreur lors de la suppression.";
      return { error: message, intent: "delete" as const };
    }
    return null;
  }

  const type = formData.get("type") as string;
  const files = formData.getAll("file") as File[];
  const validFiles = files.filter((f) => f.size > 0);

  if (validFiles.length === 0) return { error: "Veuillez sélectionner un fichier.", type };

  for (const file of validFiles) {
    if (!ALLOWED_MIMES.includes(file.type))
      return { error: `Format non autorisé : ${file.name}. Accepté : PDF, PNG, JPG.`, type };
    if (file.size > MAX_SIZE_BYTES)
      return { error: `Fichier trop volumineux : ${file.name} (max 4 Mo).`, type };
  }

  for (const file of validFiles) {
    const backendForm = new FormData();
    backendForm.append("file", file);

    const response = await fetch(`${process.env.BACKEND_URL}/api/visa-files/${type}`, {
      method: "POST",
      headers: { Accept: "application/json" },
      body: backendForm,
    });

    if (!response.ok) {
      const body = await response.text();
      throw new Error(`Upload failed: ${response.status} — ${body}`);
    }
  }

  return { success: true as const, type };
}

export default function Home() {
  const { passports, photos, forms } = useLoaderData<typeof loader>();
  const actionData = useActionData<typeof action>();

  return (
    <div className="p-6 max-w-2xl mx-auto flex flex-col gap-8">
      <h1 className="text-xl font-semibold">Visa Dossier</h1>

      <FileSection label="Passeport" type="passport" multiple={false} actionData={actionData} />
      <FileSection label="Photos" type="photo" multiple={true} actionData={actionData} />
      <FileSection label="National Visa Request Form" type="form" multiple={false} actionData={actionData} />

      <FileList label="Passeports" files={passports} actionData={actionData} />
      <FileList label="Photos" files={photos} actionData={actionData} />
      <FileList label="National Visa Request Forms" files={forms} actionData={actionData} />
    </div>
  );
}
