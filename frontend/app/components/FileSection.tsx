import { useState } from "react";
import { Form, useNavigation } from "react-router";
import type { ActionData } from "../types/visa";

type Props = {
  label: string;
  type: string;
  multiple: boolean;
  actionData: ActionData;
};

export function FileSection({ label, type, multiple, actionData }: Props) {
  const [fileNames, setFileNames] = useState<string[]>([]);
  const navigation = useNavigation();
  const isActive = "type" in (actionData ?? {}) && actionData?.type === type;
  const isUploading =
    navigation.state === "submitting" &&
    navigation.formData?.get("type") === type &&
    !navigation.formData?.get("intent");

  return (
    <div className="flex flex-col gap-2">
      <h2 className="font-medium">{label}</h2>
      <Form method="post" encType="multipart/form-data" className="flex flex-col gap-2">
        <input type="hidden" name="type" value={type} />
        <label className={`flex items-center gap-2 cursor-pointer border border-dashed rounded px-4 py-3 w-fit transition-colors ${fileNames.length > 0 ? "border-green-500 bg-green-50 text-green-700" : "border-gray-400 hover:bg-gray-50"}`}>
          <span>
            {fileNames.length === 0
              ? "Choisir un fichier"
              : fileNames.length === 1
              ? fileNames[0]
              : `${fileNames.length} fichiers sélectionnés`}
          </span>
          <input
            type="file"
            name="file"
            accept=".pdf,.png,.jpg,.jpeg"
            multiple={multiple}
            className="hidden"
            onChange={(e) => setFileNames(Array.from(e.target.files ?? []).map((f) => f.name))}
          />
        </label>

        {isActive && actionData && "error" in actionData && (
          <p className="text-sm text-red-600">{actionData.error}</p>
        )}
        {isActive && actionData && "success" in actionData && (
          <p className="text-sm text-green-600">Fichier(s) envoyé(s) avec succès.</p>
        )}

        <button
          type="submit"
          disabled={isUploading}
          className="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 w-fit disabled:opacity-50 disabled:cursor-not-allowed"
        >
          {isUploading ? "Envoi en cours…" : "Envoyer"}
        </button>
      </Form>
    </div>
  );
}
