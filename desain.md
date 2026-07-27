# Design System & UI/UX Specification
# Grownesia - Modern Purple AI E-Commerce & Economic Ecosystem

---

| Document Information | Detail |
| :--- | :--- |
| **Nama Dokumen** | Design System & UI/UX Spec |
| **Versi System** | 1.0.0 (Purple Modern Theme) |
| **Gaya Desain** | Modern Glassmorphism, Dark/Light Hybrid, Neon Glow Accents |
| **Primary Color** | Electric Purple (`#7C3AED` / `#6D28D9`) |

---

## 1. Konsep & Filosofi Desain

### 1.1 "Empowered Local Economy with Modern AI"
Desain Grownesia menggabungkan estetika **modern, futuristik, dan humanis**. Warna **Ungu (Purple)** dipilih sebagai *Primary Color* untuk melambangkan **inovasi kecerdasan buatan (AI), kemewahan lokal yang berkualitas, serta kreativitas UMKM Indonesia**.

### 1.2 Pilar Visual (Visual Pillars)
1. **Electric Purple Aesthetic:** Penggunaan warna ungu elektrik yang mencolok namun nyaman dipandang, dilengkapi efek *gradient glow* untuk elemen AI.
2. **Glassmorphism & Depth:** Komponen kartu semi-transparan dengan efek *backdrop blur* dan garis tepi halus (*subtle border*) untuk memberikan kesan kedalaman 3D yang elegan.
3. **Micro-Interactions & Feedback:** Visualisasi status real-time dengan animasi pulsa (*pulsing glow*), animasi respon AI, serta visual dampak sosial (*Impact Badge*).
4. **Inclusive Multi-Role Layout:** Antarmuka responsif yang disesuaikan secara khusus untuk Pembeli (Clean & Engaging), Pelaku Bisnis (Data-Dense & Actionable Studio), serta Pemerintah (Executive Command Center).

---

## 2. Palette Warna (Color Palette System)

```
========================================================================================
PRIMARY PURPLE PALETTE
========================================================================================
[ 50 ]  #F5F3FF  | Very Light Tint (Light Mode Background Accent)
[ 100]  #EDE9FE  | Light Tint (Hover Surface Light)
[ 200]  #DDD6FE  | Soft Lavender (Borders & Dividers)
[ 300]  #C4B5FD  | Light Violet (Subtle Text / Secondary Icons)
[ 400]  #A78BFA  | Bright Violet (Glow Effects & Badges)
[ 500]  #8B5CF6  | Vibrant Violet (Primary Accent)
[ 600]  #7C3AED  | ELECTRIC PURPLE ★ (PRIMARY MAIN)
[ 700]  #6D28D9  | Deep Purple (Primary Hover / Dark Mode Accent)
[ 800]  #5B21B6  | Royal Amethyst (Primary Active / Headers)
[ 900]  #4C1D95  | Dark Purple (Card Fill Dark Mode)
[ 950]  #2E1065  | Deepest Violet (Background Ambient Base)
========================================================================================
```

### 2.1 Color Tokens Matrix

| Category | Token Name | Color Hex / Value | Usage Description |
| :--- | :--- | :--- | :--- |
| **Primary Main** | `color-primary-main` | `#7C3AED` | Tombol CTA Utama, Header Active, Highlight AI, Icon Aktif |
| **Primary Hover** | `color-primary-hover` | `#6D28D9` | State Hover Tombol Utama & Card Interactive |
| **Primary Light** | `color-primary-light` | `#A78BFA` | Glow Borders, Chip Text, AI Sparkle Accent |
| **Accent AI Neon** | `color-accent-magenta`| `#EC4899` | Gradient Badge AI Assistant, High Priority Alert |
| **Accent Impact** | `color-accent-emerald`| `#10B981` | Impact Score Chip, Pertumbuhan Omzet Positif |
| **Accent Analytics**| `color-accent-indigo` | `#4F46E5` | Visualisasi Data Grafik, Secondary Chart Metric |
| **Accent Warning** | `color-accent-amber`  | `#F59E0B` | Warning AI Product Optimizer, Peringatan Stok |
| **Dark Background** | `color-bg-dark`       | `#0B0F19` | Dark Mode Base Background |
| **Dark Card Glass** | `color-card-dark-glass`| `rgba(23, 27, 44, 0.75)` | Kartu Glassmorphic + `backdrop-filter: blur(16px)` |
| **Light Background**| `color-bg-light`      | `#F8FAFC` | Light Mode Base Background |
| **Light Card Surface**| `color-card-light`   | `#FFFFFF` | Light Mode Card Surface |

---

## 3. Tipografi & Skala Spasi (Typography & Spacing)

### 3.1 Font Family
* **Primary Sans-Serif:** `Plus Jakarta Sans`, `-apple-system`, `BlinkMacSystemFont`, `Segoe UI`, `Roboto`, `sans-serif`.
* **Monospace Data:** `JetBrains Mono`, `Fira Code`, `Courier New`.

### 3.2 Typography Scale Matrix

| Element | Size | Weight | Line Height | CSS Utility Equivalent |
| :--- | :--- | :--- | :--- | :--- |
| **Display H1** | 36px | 800 (ExtraBold) | 44px | `text-4xl font-extrabold tracking-tight` |
| **Heading H2** | 28px | 700 (Bold) | 36px | `text-3xl font-bold` |
| **Heading H3** | 22px | 600 (SemiBold)| 28px | `text-2xl font-semibold` |
| **Subheading H4**| 18px | 600 (SemiBold)| 24px | `text-lg font-semibold` |
| **Body Large** | 16px | 400/500 | 24px | `text-base` |
| **Body Regular**| 14px | 400 (Normal) | 20px | `text-sm` |
| **Caption / Small**| 12px | 500 (Medium) | 16px | `text-xs font-medium` |

### 3.3 System Grid & Spacing
* **Grid Base:** 8px base grid system (4px, 8px, 12px, 16px, 24px, 32px, 48px, 64px).
* **Border Radii:**
  * Small (Buttons, Input): `8px` (`rounded-lg`)
  * Medium (Cards, Modals): `16px` (`rounded-2xl`)
  * Large (Floating Drawer, Banner): `24px` (`rounded-3xl`)
  * Pill (Badges, Tags): `9999px` (`rounded-full`)

---

## 4. Spesifikasi Komponen UI Utama (UI Components Spec)

### 4.1 AI Assistant Chat Overlay Widget
* **Tampilan Visual:** Floating Drawer di sudut kanan bawah dengan efek *Glassmorphism Purple Ambient Glow*.
* **Header Widget:** Background Gradient `linear-gradient(135deg, #7C3AED 0%, #EC4899 100%)` dengan tulisan **"Grownesia AI Shopping Assistant"** + Icon Sparkle (*Glowing Pulse*).
* **Quick Prompt Chips:** Tombol pill bertuliskan kueri cepat:
  1. 🎁 *"Hadiah untuk ibu umur 50th"*
  2. 💰 *"Rekomendasi Paket Budget Rp300.000"*
  3. ⚖️ *"Bandingkan Kopi Gula Aren vs Kopi Klepon"*
* **Bubble Chat:**
  * **User Chat:** Purple Dark Background (`#5B21B6`), teks putih, rata kanan.
  * **AI Chat:** Glassmorphism Surface dengan border ungu tipis (`border: 1px solid rgba(167, 139, 250, 0.3)`), teks terang, dilengkapi tombol action: `"Tambahkan Semua Paket ke Keranjang"`.

### 4.2 Impact Score Badge & Chip
* **Visual Concept:** Menampilkan kontribusi sosial ekonomi dari pembelian produk secara transparan.
* **Komponen CSS:**
```css
.impact-badge {
  background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(124, 58, 237, 0.15) 100%);
  border: 1px solid rgba(16, 185, 129, 0.4);
  color: #10B981;
  padding: 6px 14px;
  border-radius: 9999px;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}
```
* **Isi Teks:** `🌱 Impact Score: +3 Pekerja Lokal & 1 Desa Berkembang`

### 4.3 AI Product Optimizer Card (Business Dashboard)
* **Tampilan Visual:** Kartu interaktif saat pelaku UMKM mengunggah produk.
* **Alert Box:** Warna Amber Warning (`#F59E0B`) dengan border menyala:
  * `⚠️ AI Detection: Foto terlalu gelap (Kecerahan 35%)`
  * `💡 Saran AI: Klik tombol "Optimasi Foto" untuk meningkatkan pencahayaan otomatis.`
* **Tombol Optimasi AI:** Gradient Purple Button dengan efek shimmer animation.

### 4.4 Business Health & AI Insight Analytics Card
* **Metrik Utama:** Card dengan angka Omzet `Rp45.250.000` (+18.4% vs bulan lalu).
* **Business Health Score Gauge:** Circular progress ring bertuliskan **`Score 88/100 (Sangat Baik)`** dengan indikator warna ungu-hijau.
* **AI Insight Banner:**
  * `🤖 AI Insight: "Produk Kopi Klepon meningkat pesat di wilayah Bandung. Disarankan menambah stok 50 unit sebelum akhir pekan."`

### 4.5 Government Economic Command Center Heatmap & Policy Simulator
* **Map Layer:** Peta interaktif daerah dengan gradasi warna intensitas aktivitas ekonomi (Ungu Muda `#C4B5FD` ke Ungu Pekat `#2E1065`).
* **Policy Simulator Interface:**
  * **Interactive Slider:** Parameter *Subsidi Ongkir (0% - 50%)*, *Bantuan Alat Produksi*, *Pelatihan Digital*.
  * **Graph Output Real-time:** Grafik proyeksi kenaikan omzet agregat UMKM & penyerapan tenaga kerja dengan garis prediksi berwarna Magenta Glow (`#EC4899`).

---

## 5. Panduan Micro-Interactions & Animasi

### 5.1 CSS Animations Standard

#### Hover Glowing Button
```css
.btn-primary-purple {
  background: #7C3AED;
  color: #FFFFFF;
  border-radius: 10px;
  padding: 12px 24px;
  font-weight: 600;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 4px 14px 0 rgba(124, 58, 237, 0.39);
}

.btn-primary-purple:hover {
  background: #6D28D9;
  transform: translateY(-2px);
  box-shadow: 0 8px 25px 0 rgba(124, 58, 237, 0.6);
}
```

#### AI Pulsing Effect (Sparkle / Active AI Engine)
```css
@keyframes purple-pulse {
  0% {
    box-shadow: 0 0 0 0 rgba(124, 58, 237, 0.7);
  }
  70% {
    box-shadow: 0 0 0 12px rgba(124, 58, 237, 0);
  }
  100% {
    box-shadow: 0 0 0 0 rgba(124, 58, 237, 0);
  }
}

.ai-active-pulse {
  animation: purple-pulse 2s infinite ease-in-out;
}
```

---

## 6. Layout Wireframe & User Flow Architecture

### 6.1 Layout Overview per Role

```
+-----------------------------------------------------------------------------------+
| TOP NAVBAR: Grownesia Logo | Role Switcher | Search (AI Powered) | Cart | Profile  |
+-----------------------------------------------------------------------------------+
|                                                                                   |
|  [ROLE: CONSUMER]                                                                 |
|  +-----------------------------------+  +--------------------------------------+  |
|  | Hero Banner (Modern Purple Glow)  |  | AI Shopping Assistant Floating Widget|  |
|  | "Belanja Lokal Berdampak AI"      |  | [ Hadiah Ibu 50th | Budget 300rb ]    |  |
|  +-----------------------------------+  +--------------------------------------+  |
|  | Product Grid with Impact Badges   |  | AI Comparison Drawer                 |  |
|  +-----------------------------------+  +--------------------------------------+  |
|                                                                                   |
|  [ROLE: BUSINESS ACCOUNT]                                                         |
|  +------------------------------------+  +-------------------------------------+  |
|  | Omzet & Business Health (88/100)  |  | AI Marketing Studio & Omnichannel   |  |
|  +------------------------------------+  +-------------------------------------+  |
|  | AI Product Optimizer & Inventory   |  | AI Financial Assistant (BEP & Cash) |  |
|  +------------------------------------+  +-------------------------------------+  |
|                                                                                   |
|  [ROLE: GOVERNMENT ACCOUNT]                                                       |
|  +------------------------------------+  +-------------------------------------+  |
|  | Regional Economic Map (Heatmap)    |  | AI Policy Simulator (Sliders & Graph)|  |
|  +------------------------------------+  +-------------------------------------+  |
+-----------------------------------------------------------------------------------+
```

---
*Spesifikasi sistem desain ini menjadi acuan utama pengembang frontend dan desainer UI/UX platform Grownesia.*
