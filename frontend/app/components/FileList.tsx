import { Form, useNavigation } from "react-router";
import type { ActionData, VisaFile } from "~/types/visa";

type Props = {
  label: string;
  files: VisaFile[];
  actionData: ActionData;
};

export function FileList({ label, files, actionData }: Props) {
  const navigation = useNavigation();
  const deletingId =
    navigation.state === "submitting" &&
    navigation.formData?.get("intent") === "delete"
      ? navigation.formData.get("id")
      : null;

  return (
    <div>
      <h2 className="font-medium mb-2">{label} ({files.length})</h2>
      {actionData && "intent" in actionData && actionData.intent === "delete" && actionData.error && (
        <p className="text-sm text-red-600 mb-1">{actionData.error}</p>
      )}
      {files.length === 0 ? (
        <p className="text-gray-500 text-sm">Aucun fichier.</p>
      ) : (
        <ul className="flex flex-col gap-1">
          {files.map((f) => (
            <li key={f.id} className="flex items-center justify-between border rounded px-3 py-2 text-sm">
              <span className={deletingId === f.id ? "opacity-40" : ""}>{f.originalName}</span>
              <Form method="post">
                <input type="hidden" name="intent" value="delete" />
                <input type="hidden" name="id" value={f.id} />
                <button
                  type="submit"
                  disabled={deletingId === f.id}
                  className="text-gray-400 hover:text-red-600 transition-colors disabled:opacity-30 disabled:cursor-not-allowed"
                  title="Supprimer"
                >
                  🗑
                </button>
              </Form>
            </li>
          ))}
        </ul>
      )}
    </div>
  );
}
