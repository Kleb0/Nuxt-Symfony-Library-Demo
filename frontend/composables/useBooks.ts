import { ref } from 'vue';

export interface Book {
  id: number;
  titre: string;
  auteur: string;
  unitPrice: string;
  resume: string;
  image: string;
  categorie: string;
}

export interface BooksApiResponse {
  'hydra:member'?: Book[];
  'hydra:totalItems'?: number;
  member?: Book[];
  totalItems?: number;
}

export const useBooks = () => {
  const loading = ref(false);
  const error = ref<string | null>(null);

  const fetchBooks = async (): Promise<Book[]> => {
    loading.value = true;
    error.value = null;

    try {
      const response = await fetch('http://localhost:8000/api/books');
      
      if (!response.ok) {
        throw new Error(`Erreur HTTP: ${response.status} - Vérifiez que le serveur Symfony est démarré sur le port 8000`);
      }
      
      const data: BooksApiResponse = await response.json();
      return data['hydra:member'] || data.member || [];
      
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Erreur inconnue';
      return [];
    } finally {
      loading.value = false;
    }
  };

  return {
    loading,
    error,
    fetchBooks
  };
};