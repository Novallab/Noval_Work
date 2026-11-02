import { useRouter } from "expo-router";
import { useEffect, useState } from "react";
import { Pressable, ScrollView, Text, View } from "react-native";
import { type Barang, daftarBarang, filterStokMenipis, hitungTotalStok } from "../data/barang";
import { styles } from "../styles/katalogStyles";

export default function Katalog() {
  const router = useRouter();
  const [hanyaMenipis, setHanyaMenipis] = useState<boolean>(false);
  const [tampil, setTampil] = useState<Barang[]>(daftarBarang);

  useEffect(() => {
    setTampil(hanyaMenipis ? filterStokMenipis(daftarBarang) : daftarBarang);
  }, [hanyaMenipis]);

  const kembali = () => {
    if (router.canGoBack()) {
      router.back();
    } else {
      router.replace("/");
    }
  };

  return (
    <ScrollView style={styles.container}>
      <Text style={styles.summary}>
        Total stok: {hitungTotalStok(daftarBarang)} | Ditampilkan: {tampil.length} barang
      </Text>

      <Pressable style={styles.button} onPress={() => setHanyaMenipis(!hanyaMenipis)}>
        <Text style={styles.buttonText}>
          {hanyaMenipis ? "Tampilkan Semua" : "Hanya Stok Menipis"}
        </Text>
      </Pressable>

      {tampil.map((item) => (
        <View key={item.id} style={styles.card}>
          <Text style={styles.itemName}>{item.nama}</Text>
          <Text style={[styles.itemStock, { color: item.stok < 3 ? "#f87171" : "#4ade80" }]}>
            Stok: {item.stok} {item.satuan}
          </Text>
        </View>
      ))}

      <Pressable style={styles.button} onPress={kembali}>
        <Text style={styles.buttonText}>Kembali</Text>
      </Pressable>
    </ScrollView>
  );
}