# Rangkuman Git — Push & Force Push

## 1. Error Push Ditolak

**Perintah:**
```bash
git push -u origin septa
```

**Output:**
```
! [rejected]        septa -> septa (fetch first)
error: failed to push some refs to 'https://github.com/...'
hint: Updates were rejected because the remote contains work that you do not
hint: have locally.
```

**Artinya:**
Remote branch `septa` memiliki commit yang tidak ada di lokal kamu. Git menolak push demi keamanan.

---

## 2. Solusi Normal (Disarankan)

Ambil perubahan remote lalu gabungkan:

```bash
git pull origin septa --rebase
git push -u origin septa
```

**`--rebase`** membuat commit kamu diletakkan di atas commit remote, history tetap rapi tanpa merge commit.

**Peringatan:** `git pull` bisa mengubah struktur/code jika ada perubahan dari orang lain.

---

## 3. Mengecek Commit Remote (Sebelum Force)

### a. Ambil data remote tanpa menggabungkan:

```bash
git fetch origin septa
```

Perintah ini hanya mengunduh data, tidak mengubah file lokal.

### b. Lihat daftar commit yang ada di remote tapi belum di lokal:

```bash
git log HEAD..origin/septa --oneline
```

**Format Output:**
```
a1b2c3d Fix checkout validation
e4f5g6h Add promo banner
```

### c. Lihat siapa penulis setiap commit:

```bash
git log HEAD..origin/septa --format="%h %an: %s"
```

| Format | Arti |
|--------|------|
| `%h` | Hash pendek commit |
| `%an` | Nama author |
| `%ar` | Waktu relatif (3 days ago) |
| `%s` | Pesan commit |

**Contoh Output:**
```
a1b2c3d John Doe: Fix checkout validation
e4f5g6h Jane Smith: Add promo banner
```

---

## 4. Force Push (Jika Yakin)

### a. Force biasa — berbahaya:

```bash
git push -u origin septa --force
```

**Efek:**
- Menghapus semua commit di remote yang tidak ada di lokal
- Commit orang lain **hilang permanen** dari branch ini
- Tidak ada peringatan, langsung timpa

### b. Force dengan keamanan (disarankan):

```bash
git fetch origin septa
git push -u origin septa --force-with-lease
```

**Perbedaan:**

| Perintah | Bahaya |
|----------|--------|
| `--force` | Timpa remote tanpa tanya, hapus semua |
| `--force-with-lease` | Timpa hanya jika tidak ada tambahan baru sejak fetch tadi. Jika ada commit baru dari orang lain setelah fetch, perintah ditolak |

**`--force-with-lease`** lebih aman: melindungi commit orang lain yang masuk di antara waktu fetch dan push kamu.

---

## 5. Ringkasan Alur Yang Benar

```
git fetch origin septa               ← ambil data remote
git log HEAD..origin/septa --oneline ← cek isi commit remote

Jika tidak ada commit orang lain:
  git push -u origin septa --force-with-lease   ← aman untuk timpa

Jika ada commit orang lain:
  git pull origin septa --rebase                 ← ambil + gabung
  git push -u origin septa                       ← push normal

Jika hanya commit kamu sendiri (dari PC lain):
  git push -u origin septa --force-with-lease    ← safe force
```
