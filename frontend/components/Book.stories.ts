import type { Meta, StoryObj } from '@storybook/vue3';
import Book from './Book.vue';

const meta: Meta<typeof Book> = {
  title: 'Components/Book',
  component: Book,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
};

export default meta;
type Story = StoryObj<typeof meta>;

const sampleBook = {
  id: 1,
  titre: 'One Piece - Tome 1',
  auteur: 'Eiichiro Oda',
  unitPrice: '6.90',
  resume: 'Le premier tome du manga One Piece où nous découvrons Monkey D. Luffy et le début de son aventure pour devenir le Roi des Pirates.',
  image: '/images/One_Piece_1.jpg',
  categorie: 'Manga',
};

export const Default: Story = {
  args: {
    book: sampleBook,
  },
};