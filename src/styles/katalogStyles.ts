import { StyleSheet } from "react-native";

export const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: "#020617",
    padding: 24,
  },
  summary: { fontSize: 14, color: "#94a3b8", marginBottom: 16 },
  card: {
    backgroundColor: "#0f172a",
    borderRadius: 12,
    padding: 16,
    marginBottom: 12,
    borderWidth: 1,
    borderColor: "#1e293b",
  },
  itemName: { fontSize: 16, fontWeight: "600", color: "#f1f5f9" },
  itemStock: { fontSize: 14, marginTop: 4 },
  button: {
    backgroundColor: "#4f46e5",
    paddingVertical: 12,
    borderRadius: 12,
    alignItems: "center",
    marginBottom: 16,
  },
  buttonText: { color: "#fff", fontWeight: "600" },
});
