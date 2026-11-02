export type Barang = {
  id: number;
  nama: string;
  stok: number;
  satuan: string;
};

export const daftarBarang: Barang[] = [
  { id: 1, nama: "Beras", stok: 5, satuan: "kg" },
  { id: 2, nama: "Minyak Goreng", stok: 2, satuan: "liter" },
  { id: 3, nama: "Telur", stok: 12, satuan: "butir" },
  { id: 4, nama: "Gula", stok: 1, satuan: "kg" },
];

export function hitungTotalStok(daftar: Barang[]): number {
  let total = 0;
  for (const item of daftar) {
    total += item.stok;
  }
  return total;
}

export function filterStokMenipis(daftar: Barang[], batas: number = 3): Barang[] {
  return daftar.filter((item) => item.stok < batas);
}