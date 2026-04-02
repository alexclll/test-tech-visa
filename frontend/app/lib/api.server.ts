const BACKEND_URL = process.env.BACKEND_URL;

export async function getVisaFiles(type: string, page = 1, perPage = 10) {
  const url = `${BACKEND_URL}/api/visa-files?type=${type}&page=${page}&perPage=${perPage}`;
  const response = await fetch(url, { headers: { Accept: "application/json" } });
  if (!response.ok) throw new Error(`API error: ${response.status}`);
  return response.json();
}
