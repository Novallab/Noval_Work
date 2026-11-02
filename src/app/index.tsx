import { Link } from "expo-router";
import { Pressable, StyleSheet, Text, View } from "react-native";

export default function Index() {
  return (
    <View style={styles.container}>
      <View style={styles.logo}>
        <Text style={{ fontSize: 40 }}>📦</Text>
      </View>
      <Text style={styles.title}>
        Stock Kos <Text style={{ color: "#60a5fa" }}>v2</Text>
      </Text>
      <Text style={styles.subtitle}>Kelola stok kebutuhan kos kamu</Text>

      <View style={styles.row}>
        <View style={styles.pill}><Text style={styles.pillText}>🍜 Makanan</Text></View>
        <View style={styles.pill}><Text style={styles.pillText}>🧼 Mandi</Text></View>
        <View style={styles.pill}><Text style={styles.pillText}>💊 P3K</Text></View>
      </View>

      <Link href="/katalog" asChild>
        <Pressable style={styles.button}>
          <Text style={styles.buttonText}>Mulai →</Text>
        </Pressable>
      </Link>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    alignItems: "center",
    justifyContent: "center",
    backgroundColor: "#020617",
    padding: 24,
  },
  logo: {
    width: 88,
    height: 88,
    borderRadius: 24,
    backgroundColor: "#1e293b",
    borderWidth: 1,
    borderColor: "#334155",
    alignItems: "center",
    justifyContent: "center",
    marginBottom: 20,
  },
  title: { color: "#fff", fontSize: 34, fontWeight: "800" },
  subtitle: { color: "#94a3b8", fontSize: 16, marginTop: 6, marginBottom: 20 },
  row: { flexDirection: "row", gap: 8, marginBottom: 28 },
  pill: {
    backgroundColor: "#0f172a",
    borderWidth: 1,
    borderColor: "#334155",
    paddingHorizontal: 12,
    paddingVertical: 6,
    borderRadius: 999,
  },
  pillText: { color: "#cbd5e1", fontSize: 13 },
  button: {
    backgroundColor: "#2563eb",
    paddingVertical: 14,
    paddingHorizontal: 40,
    borderRadius: 14,
  },
  buttonText: { color: "#fff", fontSize: 17, fontWeight: "700" },
});