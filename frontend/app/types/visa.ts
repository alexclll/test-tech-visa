export type VisaFile = { id: string; originalName: string };

export type ActionData =
  | { error: string; type: string; intent?: never; success?: never }
  | { error: string; intent: "delete"; type?: never; success?: never }
  | { success: true; type: string; intent?: never; error?: never }
  | null
  | undefined;
