const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api';

async function fetcher<T>(url: string, options?: RequestInit): Promise<T> {
  const response = await fetch(`${API_URL}${url}`, {
    ...options,
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      ...options?.headers,
    },
  });

  if (!response.ok) {
    throw new Error(`HTTP error! status: ${response.status}`);
  }

  if (response.status === 204) {
    return null as T;
  }

  return response.json();
}

export const api = {
  get: <T>(url: string) => fetcher<T>(url),
  post: <T>(url: string, data: unknown) => fetcher<T>(url, { method: 'POST', body: JSON.stringify(data) }),
  patch: <T>(url: string, data?: unknown) => fetcher<T>(url, { method: 'PATCH', body: data ? JSON.stringify(data) : undefined }),
  delete: <T>(url: string) => fetcher<T>(url, { method: 'DELETE' }),
};
