function grownesiaUserDashboard() {
  const initData = window.dashboardInitialData || {};

  return {
    products: initData.products || [],
    searchQuery: '',
    activeCategory: 'all',
    selectedImpactFilter: 'all',
    activeTab: initData.activeTab || 'katalog',
    initialProductId: initData.initialProductId || null,
    showFloatingAiWidget: false,
    sidebarMobileOpen: false,

    userProfile: initData.userProfile || {},

    favorites: [1, 3],

    reviews: initData.reviews || [],

    ordersHistory: initData.ordersHistory || [],

    trackingOrder: null,
    copyToast: false,
    isRefreshingTracking: false,

    reviewingOrder: null,
    newReviewForm: {
      rating: 5,
      comment: ''
    },

    notifications: initData.notifications || [],

    unreadNotifCount: initData.unreadNotifCount || 0,

    selectedProductDetail: null,

    userImpact: initData.userImpact || { totalJobs: 8, villagesHelped: 3, craftswomenHelped: 5 },

    cart: [],
    selectedPaymentMethod: 'qris',
    lastOrderImpactSummary: '',

    customChatInput: '',
    aiIsTyping: false,
    chatMessages: [
      {
        sender: 'ai',
        text: 'Halo Budi! Saya AI Assistant Anda. Silakan tanyakan saran produk atau hadiah yang Anda butuhkan.',
      }
    ],

    giftBudget: 300000,
    giftRecipientMode: 'preset',
    giftRecipient: 'Hadiah untuk Ibu / Orang Tua (Usia 50th)',
    customGiftRecipientInput: '',
    generatedBundle: null,

    getPageTitle() {
      switch (this.activeTab) {
        case 'detail':
          return this.selectedProductDetail ? this.selectedProductDetail.name : 'Detail Produk';
        case 'cart':
          return 'Keranjang Belanja';
        case 'checkout':
          return 'Checkout Pesanan';
        case 'orders':
        case 'write-review':
          return 'Pesanan Saya';
        case 'tracking':
          return 'Lacak Pengiriman';
        case 'profile':
          return 'Profil & Akun';
        case 'ai-assistant':
          return 'AI Assistant';
        case 'ai-gift':
          return 'Rekomendasi Kado AI';
        case 'ai-compare':
          return 'Komparasi Produk AI';
        case 'favorites':
          return 'Produk Favorit';
        case 'success-impact':
          return 'Sertifikat Dampak';
        case 'katalog':
        default:
          return 'Katalog Produk';
      }
    },

    getPathForTab(tab, product = null) {
      switch (tab) {
        case 'detail':
          const prodId = product?.id || this.selectedProductDetail?.id || 1;
          return '/produk/' + prodId;
        case 'cart':
          return '/cart';
        case 'checkout':
          return '/checkout';
        case 'orders':
        case 'write-review':
          return '/orders';
        case 'tracking':
          return '/tracking';
        case 'profile':
          return '/profile';
        case 'ai-assistant':
          return '/ai-assistant';
        case 'ai-gift':
          return '/ai-gift';
        case 'ai-compare':
          return '/ai-compare';
        case 'favorites':
          return '/favorites';
        case 'katalog':
        default:
          return '/produk';
      }
    },

    updateUrl(pushState = true) {
      const path = this.getPathForTab(this.activeTab, this.selectedProductDetail);
      document.title = this.getPageTitle() + ' — Grownesia AI';
      if (window.location.pathname !== path) {
        if (pushState) {
          window.history.pushState({ tab: this.activeTab, prodId: this.selectedProductDetail?.id }, '', path);
        } else {
          window.history.replaceState({ tab: this.activeTab, prodId: this.selectedProductDetail?.id }, '', path);
        }
      }
    },

    handlePopState() {
      const path = window.location.pathname.replace(/^\/+|\/+$/g, '');
      if (path.startsWith('produk/') || path.startsWith('products/')) {
        const parts = path.split('/');
        const id = parseInt(parts[1]);
        const found = this.products.find(p => p.id == id);
        if (found) {
          this.selectedProductDetail = found;
        }
        this.activeTab = 'detail';
      } else if (path === 'checkout') {
        this.activeTab = 'cart';
      } else if (path === 'cart' || path === 'keranjang') {
        this.activeTab = 'cart';
      } else if (path === 'orders' || path === 'pesanan') {
        this.activeTab = 'orders';
      } else if (path === 'tracking' || path === 'lacak') {
        this.activeTab = 'tracking';
      } else if (path === 'profile' || path === 'profil') {
        this.activeTab = 'profile';
      } else if (path === 'ai-assistant') {
        this.activeTab = 'ai-assistant';
      } else if (path === 'ai-gift') {
        this.activeTab = 'ai-gift';
      } else if (path === 'ai-compare') {
        this.activeTab = 'ai-compare';
      } else if (path === 'favorites' || path === 'favorit') {
        this.activeTab = 'favorites';
      } else {
        this.activeTab = 'katalog';
      }
      document.title = this.getPageTitle() + ' — Grownesia AI';
    },

    shareProductToWhatsapp(product) {
      const prod = product || this.selectedProductDetail;
      if (!prod) return;
      const shareUrl = window.location.origin + '/produk/' + prod.id;
      const text = `Halo! Saya rekomendasikan produk UMKM *${prod.name}* dari ${prod.umkm} (${this.formatRupiah(prod.price)}).\nLihat selengkapnya di Grownesia:\n${shareUrl}`;
      window.open(`https://api.whatsapp.com/send?text=${encodeURIComponent(text)}`, '_blank');
    },

    shareCheckoutToWhatsapp() {
      const shareUrl = window.location.origin + '/checkout';
      const text = `Halo! Ini link checkout belanja produk UMKM saya di Grownesia:\n${shareUrl}`;
      window.open(`https://api.whatsapp.com/send?text=${encodeURIComponent(text)}`, '_blank');
    },

    copyCurrentUrl() {
      const url = window.location.href;
      if (navigator.clipboard) {
        navigator.clipboard.writeText(url);
      }
      this.copyToast = true;
      setTimeout(() => { this.copyToast = false; }, 2500);
    },

    async saveCart() {
      const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
      try {
        await fetch('/cart/sync', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': token || ''
          },
          body: JSON.stringify({ cart: this.cart })
        });
      } catch (e) {
        console.error('Failed to sync cart to database:', e);
      }
    },

    async loadCart() {
      try {
        let response = await fetch('/cart', {
          headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (response.ok) {
          this.cart = await response.json();
        }
      } catch (e) {
        console.error('Failed to load cart from database:', e);
      }
    },

    async initDashboard() {
      await this.loadCart();

      if (this.initialProductId) {
        const found = this.products.find(p => p.id == this.initialProductId);
        if (found) {
          this.selectedProductDetail = found;
        }
      }
      if (!this.selectedProductDetail && this.products.length > 0) {
        this.selectedProductDetail = this.products[0];
      }

      if (this.products.length >= 2) {
        this.compareProduct1 = this.products[0];
        this.compareProduct2 = this.products[4] || this.products[1];
        this.compareProduct1Id = this.compareProduct1.id;
        this.compareProduct2Id = this.compareProduct2.id;
        this.generateAiComparison();
      }
      if (this.ordersHistory.length > 0) {
        this.trackingOrder = this.ordersHistory[1] || this.ordersHistory[0];
      }
      this.generateGiftBundle();

      // Sync initial URL path without adding duplicate history entry
      this.updateUrl(false);

      // Watchers for URL state synchronization
      this.$watch('activeTab', () => this.updateUrl(true));
      this.$watch('selectedProductDetail', () => {
        if (this.activeTab === 'detail') this.updateUrl(true);
      });

      window.addEventListener('popstate', () => this.handlePopState());
    },

    openTracking(order) {
      this.trackingOrder = order;
      this.activeTab = 'tracking';
      window.scrollTo({ top: 0, behavior: 'smooth' });
    },

    copyTrackingNumber(resi) {
      if (navigator.clipboard) {
        navigator.clipboard.writeText(resi);
      }
      this.copyToast = true;
      setTimeout(() => { this.copyToast = false; }, 2500);
    },

    async refreshTracking() {
      this.isRefreshingTracking = true;
      if (this.trackingOrder && this.trackingOrder.db_id) {
        try {
          let res = await fetch('/user/shipping/' + this.trackingOrder.db_id);
          if (res.ok) {
            let data = await res.json();
            if (data.success && data.order) {
              this.trackingOrder = data.order;
              let idx = this.ordersHistory.findIndex(o => o.db_id === data.order.db_id);
              if (idx !== -1) {
                this.ordersHistory[idx] = data.order;
              }
            }
          }
        } catch (err) {
          console.error('Error refreshing tracking info:', err);
        }
      }
      setTimeout(() => { this.isRefreshingTracking = false; }, 600);
    },

    openProductDetail(product) {
      this.selectedProductDetail = product;
      this.activeTab = 'detail';
      window.scrollTo({ top: 0, behavior: 'smooth' });
    },

    getProductReviews(productId) {
      return this.reviews.filter(r => r.productId === productId);
    },

    openWriteReview(order) {
      this.reviewingOrder = order;
      this.newReviewForm.rating = 5;
      this.newReviewForm.comment = '';
      this.activeTab = 'write-review';
      window.scrollTo({ top: 0, behavior: 'smooth' });
    },

    submitProductReview() {
      if (!this.newReviewForm.comment.trim()) {
        alert('Mohon tuliskan ulasan pengalaman belanja Anda.');
        return;
      }

      this.reviews.unshift({
        id: Date.now(),
        productId: this.reviewingOrder.productId,
        userName: this.userProfile.name,
        rating: parseInt(this.newReviewForm.rating),
        date: 'Hari ini',
        comment: this.newReviewForm.comment,
        verified: true
      });

      this.reviewingOrder.reviewed = true;

      alert('Ulasan Anda telah berhasil dipublikasikan! Terima kasih telah mengulas produk UMKM mitra.');
      this.activeTab = 'orders';
    },

    toggleFavorite(product) {
      const id = product.id;
      const idx = this.favorites.indexOf(id);
      if (idx > -1) {
        this.favorites.splice(idx, 1);
      } else {
        this.favorites.push(id);
      }
    },

    isFavorite(productId) {
      return this.favorites.includes(productId);
    },

    get favoriteProducts() {
      return this.products.filter(p => this.favorites.includes(p.id));
    },

    markAllNotifRead() {
      this.unreadNotifCount = 0;
    },

    async saveProfile() {
      const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
      try {
        let response = await fetch('/user/profile/update', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': token || ''
          },
          body: JSON.stringify({
            name: this.userProfile.name,
            email: this.userProfile.email,
            phone: this.userProfile.phone,
            address: this.userProfile.address
          })
        });

        if (response.ok) {
          let data = await response.json();
          if (data && data.success) {
            this.userProfile.phone = data.user.phone;
            alert('Profil dan Alamat berhasil diperbarui!\nWhatsApp ID terhubung: ' + data.user.phone);
            this.activeTab = 'katalog';
          } else {
            alert('Gagal memperbarui profil: ' + (data.message || 'Error'));
          }
        } else {
          let errData = await response.json().catch(() => ({}));
          alert('Gagal memperbarui profil: ' + (errData.message || 'Status ' + response.status));
        }
      } catch (e) {
        console.error('Failed to save profile:', e);
        alert('Terjadi kesalahan saat menyimpan profil.');
      }
    },

    get filteredProducts() {
      return this.products.filter(p => {
        const matchesSearch = p.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
          p.umkm.toLowerCase().includes(this.searchQuery.toLowerCase());
        const matchesCat = this.activeCategory === 'all' || p.category === this.activeCategory;
        return matchesSearch && matchesCat;
      });
    },

    get cartTotalCount() {
      return this.cart.reduce((sum, item) => sum + (item.product ? item.qty : 0), 0);
    },

    get cartTotalPrice() {
      return this.cart.reduce((sum, item) => sum + (item.product ? (item.product.price * item.qty) : 0), 0);
    },

    addToCart(product, redirect = true) {
      const idx = this.cart.findIndex(i => i.product && i.product.id === product.id);
      if (idx > -1) {
        this.cart[idx].qty++;
      } else {
        this.cart.push({ product: product, qty: 1 });
      }
      this.saveCart();
      if (redirect) {
        this.activeTab = 'cart';
        window.scrollTo({ top: 0, behavior: 'smooth' });
      }
    },

    updateQty(idx, change) {
      this.cart[idx].qty += change;
      if (this.cart[idx].qty <= 0) {
        this.cart.splice(idx, 1);
      }
      this.saveCart();
    },

    calculateCartImpactText() {
      if (this.cart.length === 0) return '0 Pekerja';
      let count = this.cart.reduce((acc, item) => acc + (item.product ? (2 * item.qty) : 0), 0);
      return count + ' Pekerja Lokal & ' + Math.ceil(count / 2) + ' Desa Terbantu';
    },

    async processPaymentSuccess() {
      const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
      if (this.cart.length > 0) {
        try {
          await fetch('/checkout', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': token || ''
            },
            body: JSON.stringify({ cart: this.cart })
          });
        } catch (err) {
          console.error('Gagal mengirim pesanan ke server:', err);
        }
      }

      const newOrderId = 'GRW-2026-' + Math.floor(1000 + Math.random() * 9000);
      const firstProd = this.cart.length > 0 ? this.cart[0].product : (this.products[0] || { id: 1, name: 'Produk UMKM' });
      const itemsSummary = this.cart.length > 0 ? this.cart.map(c => `${c.product.name} (${c.qty}x)`).join(', ') : 'Pesanan UMKM';

      this.ordersHistory.unshift({
        id: newOrderId,
        date: 'Hari ini',
        productId: firstProd.id,
        productName: firstProd.name,
        items: itemsSummary,
        total: this.cartTotalPrice,
        status: 'Selesai',
        impact: '3 Pekerja Terbantu',
        reviewed: false
      });

      this.lastOrderImpactSummary = "3 Pekerja Lokal, 1 Desa Berkembang, & 2 Penenun Terbantu";
      this.userImpact.totalJobs += 3;
      this.userImpact.villagesHelped += 1;
      this.cart = [];
      this.saveCart();
      this.activeTab = 'success-impact';
      window.scrollTo({ top: 0, behavior: 'smooth' });
    },

    scrollToBottom() {
      this.$nextTick(() => {
        ['#chatContainer', '#fullChatContainer'].forEach(selector => {
          const el = document.querySelector(selector);
          if (el) {
            el.scrollTo({
              top: el.scrollHeight,
              behavior: 'smooth'
            });
          }
        });
      });
    },

    async sendAiQuery(queryText) {
      if (!queryText.trim()) return;
      this.chatMessages.push({ sender: 'user', text: queryText });
      this.customChatInput = '';
      this.aiIsTyping = true;
      this.scrollToBottom();

      const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

      try {
        let response = await fetch('/user/ai/chat', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': token || ''
          },
          body: JSON.stringify({ message: queryText })
        });

        this.aiIsTyping = false;

        if (!response.ok) {
          console.warn('AI Chat request returned status:', response.status);
          this.chatMessages.push({
            sender: 'ai',
            text: 'Grownesia AI Shopping Assistant siap membantu Anda menjelajahi produk-produk UMKM lokal pilihan.',
            products: [this.products[0]]
          });
          this.scrollToBottom();
          return;
        }

        let data = await response.json();

        if (data && data.reply) {
          const replyLower = data.reply.toLowerCase();

          // Filter products mentioned in the AI's reply
          let matchedProds = this.products.filter(p => {
            const pName = p.name.toLowerCase();
            const stopWords = ['original', 'premium', 'asli', 'signature', 'modern', 'paket', '200g', '250g', '500ml', '350ml', 'isi', '10', 'reserve', 'klasik', '500g', 'level', '5', 'tulis', 'motif', 'cap', 'ikat', 'handmade', 'royal', 'violet'];
            const words = pName.split(/[\s,\-\./]+/).filter(w => w.length >= 3 && !stopWords.includes(w));

            if (words.length === 0) return false;
            const matchCount = words.filter(w => replyLower.includes(w)).length;
            return matchCount >= Math.min(2, words.length);
          });

          // Fallback to user query matching if no products are explicitly mentioned in the reply
          if (matchedProds.length === 0) {
            const qLower = queryText.toLowerCase();
            const words = qLower.split(' ').filter(w => w.length >= 3 && !['ada', 'apa', 'yang', 'dan', 'atau', 'saya', 'bisa', 'tolong', 'produk'].includes(w));
            if (words.length > 0) {
              matchedProds = this.products.filter(p => {
                const pName = (p.name || '').toLowerCase();
                const pCat = (p.category || '').toLowerCase();
                const pDesc = (p.description || '').toLowerCase();
                return words.some(w => pName.includes(w) || pCat.includes(w) || pDesc.includes(w));
              }).slice(0, 3);
            }
          }

          if (matchedProds.length === 0) {
            matchedProds = [this.products[0], this.products[1]].filter(Boolean);
          }

          this.chatMessages.push({
            sender: 'ai',
            text: data.reply,
            products: matchedProds.filter(Boolean)
          });
        } else {
          this.chatMessages.push({
            sender: 'ai',
            text: 'Grownesia AI Assistant siap membantu Anda menjelajahi produk-produk UMKM lokal pilihan.',
            products: [this.products[0]]
          });
        }
        this.scrollToBottom();
      } catch (err) {
        console.error('Error fetching AI Chat response:', err);
        this.aiIsTyping = false;
        this.chatMessages.push({
          sender: 'ai',
          text: 'Rekomendasi dari Grownesia AI Shopping Assistant untuk Anda:',
          products: [this.products[0]]
        });
        this.scrollToBottom();
      }
    },

    async generateGiftBundle() {
      let recipientText = (this.giftRecipientMode === 'custom' && this.customGiftRecipientInput.trim() !== '')
        ? this.customGiftRecipientInput
        : this.giftRecipient;

      const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

      try {
        let response = await fetch('/user/ai/gift-recommend', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': token || ''
          },
          body: JSON.stringify({
            query: recipientText,
            budget: this.giftBudget
          })
        });

        if (response.ok) {
          let data = await response.json();
          if (data && data.success && data.products) {
            this.generatedBundle = {
              recipientLabel: recipientText,
              bundleName: data.bundle_name || 'Paket Spesial UMKM',
              items: data.products,
              totalPrice: data.total_price || 0,
              impact: (data.products.length * 2) + ' Pekerja Lokal & 1 Desa Terbantu',
              aiReasoning: data.narrative || `AI menyusun paket kado bernilai tinggi sesuai anggaran Anda.`,
              impactStory: data.impact_story || ''
            };
            return;
          }
        } else {
          console.warn('Gift recommend request returned status:', response.status);
        }
      } catch (err) {
        console.error('Error fetching AI Gift Recommendation:', err);
      }

      // Fallback local logic if server offline
      let sortedProducts = [...this.products];
      const qLower = recipientText.toLowerCase();

      if (qLower.includes('dosen') || qLower.includes('kantor') || qLower.includes('rekan') || qLower.includes('formal') || qLower.includes('guru')) {
        sortedProducts.sort((a, b) => (a.category === 'kopi' || a.category === 'batik') ? -1 : 1);
      } else if (qLower.includes('ibu') || qLower.includes('wanita') || qLower.includes('perempuan') || qLower.includes('bunda')) {
        sortedProducts.sort((a, b) => (a.category === 'batik' || a.category === 'kerajinan') ? -1 : 1);
      }

      let total = 0;
      let items = [];

      for (let p of sortedProducts) {
        if (total + p.price <= this.giftBudget) {
          items.push(p);
          total += p.price;
        }
      }

      this.generatedBundle = {
        recipientLabel: recipientText,
        bundleName: 'Paket Spesial UMKM',
        items: items,
        totalPrice: total,
        impact: items.length * 2 + ' Pekerja Lokal & 1 Desa Terbantu',
        aiReasoning: `AI menganalisis kriteria "${recipientText}" dan menyusun paket berisi ${items.length} produk pilihan UMKM dengan total harga ${this.formatRupiah(total)}.`
      };
    },

    addBundleToCart() {
      if (!this.generatedBundle || !this.generatedBundle.items.length) return;
      for (let item of this.generatedBundle.items) {
        this.addToCart(item);
      }
    },

    async generateAiComparison() {
      this.compareProduct1 = this.products.find(p => p.id == this.compareProduct1Id) || null;
      this.compareProduct2 = this.products.find(p => p.id == this.compareProduct2Id) || null;

      let p1 = this.compareProduct1;
      let p2 = this.compareProduct2;
      if (!p1 || !p2) return;

      this.aiCompareAnalysis = {
        verdict: 'AI sedang menganalisis perbandingan kedua produk...',
        recommendation: ''
      };

      const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

      try {
        let response = await fetch('/user/ai/compare', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': token || ''
          },
          body: JSON.stringify({
            product1_id: p1.id,
            product2_id: p2.id
          })
        });

        if (response.ok) {
          let data = await response.json();
          if (data && data.success) {
            this.aiCompareAnalysis = {
              verdict: data.verdict,
              recommendation: data.recommendation
            };
            return;
          }
        }
      } catch (err) {
        console.error('Error fetching AI Comparison:', err);
      }

      const price1Fmt = (prod) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(prod.price);
      this.aiCompareAnalysis = {
        verdict: (p1.price) < (p2.price)
          ? `${p1.name} (${price1Fmt(p1)}) menawarkan pilihan lebih hemat dari ${p2.name} (${price1Fmt(p2)}).`
          : `${p2.name} (${price1Fmt(p2)}) menawarkan pilihan lebih hemat dari ${p1.name} (${price1Fmt(p1)}).`,
        recommendation: `Pilih ${p1.price < p2.price ? p1.name : p2.name} untuk alternatif hemat.`
      };
    },

    formatRupiah(number) {
      return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(number);
    },

    formatAiText(text) {
      if (!text) return '';
      let str = String(text);
      str = str.replace(/\*\*(.*?)\*\*/g, '<strong class="font-bold text-amber-300">$1</strong>');
      str = str.replace(/\*(.*?)\*/g, '<em class="italic text-purple-200">$1</em>');
      str = str.replace(/\n\* /g, '<br>• ').replace(/\n- /g, '<br>• ');
      str = str.replace(/\n/g, '<br>');
      return str;
    }
  };
}
