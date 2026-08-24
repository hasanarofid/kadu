<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { 
  Coins, 
  ShoppingBag, 
  History, 
  CheckCircle2, 
  Clock, 
  Sparkles, 
  CreditCard
} from 'lucide-vue-next';

const props = defineProps({
  userTokens: Number,
  packages: Array,
  transactions: Array,
  logs: Array,
});

const activeTab = ref('packages'); // 'packages', 'history'

const form = useForm({});

const buyPackage = (pkgId) => {
  form.post(route('tokens.checkout', pkgId));
};
</script>

<template>
  <AppLayout>
    <Head title="Profil & Beli Token RPP - KADU" />

    <div class="space-y-8 text-left">
      <!-- User Token Summary Card -->
      <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-r from-indigo-950 via-slate-900 to-slate-950 border border-indigo-500/30 shadow-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
        <div class="space-y-2">
          <div class="inline-flex items-center gap-2 px-3 py-1 bg-amber-500/15 rounded-full text-amber-300 text-xxs font-bold border border-amber-500/30">
            <Coins class="w-3.5 h-3.5 text-amber-400" />
            Sisa Kuota Token RPP Anda
          </div>
          <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
            {{ userTokens }} Token Tersedia
          </h1>
          <p class="text-xs sm:text-sm text-slate-300 font-medium max-w-xl">
            Setiap 1 kali generate RPP Deep Learning Vokasi akan mengurangi 1 Token kuota Anda. Beli paket token untuk menambah kuota.
          </p>
        </div>
      </div>

      <!-- Navigation Tabs -->
      <div class="flex items-center gap-3 border-b border-slate-800 pb-4">
        <button 
          @click="activeTab = 'packages'"
          :class="[
            'px-5 py-2.5 rounded-2xl text-xs font-extrabold flex items-center gap-2 transition-all cursor-pointer',
            activeTab === 'packages' ? 'bg-indigo-600 text-white shadow' : 'bg-slate-900 text-slate-400 border border-slate-800'
          ]"
        >
          <ShoppingBag class="w-4 h-4" />
          Beli Paket Token
        </button>

        <button 
          @click="activeTab = 'history'"
          :class="[
            'px-5 py-2.5 rounded-2xl text-xs font-extrabold flex items-center gap-2 transition-all cursor-pointer',
            activeTab === 'history' ? 'bg-indigo-600 text-white shadow' : 'bg-slate-900 text-slate-400 border border-slate-800'
          ]"
        >
          <History class="w-4 h-4" />
          Histori Token & Transaksi
        </button>
      </div>

      <!-- TAB 1: Beli Paket Token -->
      <div v-if="activeTab === 'packages'" class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div 
          v-for="pkg in packages" 
          :key="pkg.id" 
          class="bg-slate-900/90 border border-slate-800 hover:border-indigo-500/50 rounded-3xl p-6 sm:p-8 shadow-xl flex flex-col justify-between space-y-6 transition-all group"
        >
          <div class="space-y-4">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-500/10 text-indigo-300 text-xxs font-extrabold border border-indigo-500/20">
              <Coins class="w-3.5 h-3.5 text-amber-400" />
              <span>{{ pkg.tokens }} TOKEN RPP</span>
            </div>

            <div>
              <h3 class="text-2xl font-black text-white group-hover:text-indigo-300 transition-colors">{{ pkg.name }}</h3>
              <p class="text-xs text-slate-400 leading-relaxed mt-2">{{ pkg.description }}</p>
            </div>

            <div class="pt-2">
              <span class="text-xxs font-bold text-slate-400 uppercase">Harga:</span>
              <p class="text-2xl font-black text-emerald-400">Rp {{ Number(pkg.price).toLocaleString('id-ID') }}</p>
            </div>
          </div>

          <button 
            @click="buyPackage(pkg.id)" 
            :disabled="form.processing"
            class="w-full py-3.5 bg-gradient-to-r from-indigo-600 via-violet-600 to-purple-600 hover:brightness-110 text-white text-xs font-extrabold rounded-2xl shadow-lg shadow-indigo-600/30 flex items-center justify-center gap-2 cursor-pointer"
          >
            <CreditCard class="w-4 h-4" />
            <span>Beli Sekarang (Midtrans)</span>
          </button>
        </div>
      </div>

      <!-- TAB 2: Histori Penggunaan & Pembelian Token -->
      <div v-if="activeTab === 'history'" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Usage History -->
        <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-4">
          <h3 class="text-base font-bold text-white flex items-center gap-2">
            <History class="w-4 h-4 text-indigo-400" />
            Histori Penggunaan Token
          </h3>

          <div v-if="logs.length === 0" class="py-8 text-center text-xs text-slate-500">
            Belum ada riwayat penggunaan token.
          </div>

          <div v-else class="divide-y divide-slate-800 text-xs">
            <div v-for="log in logs" :key="log.id" class="py-3 flex items-center justify-between">
              <div>
                <p class="font-bold text-white">{{ log.description }}</p>
                <p class="text-xxs text-slate-400">{{ new Date(log.created_at).toLocaleString('id-ID') }}</p>
              </div>
              <span class="font-bold text-rose-400">-{{ log.tokens }} Token</span>
            </div>
          </div>
        </div>

        <!-- Purchase Transactions History -->
        <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-4">
          <h3 class="text-base font-bold text-white flex items-center gap-2">
            <CreditCard class="w-4 h-4 text-emerald-400" />
            Histori Transaksi Pembelian
          </h3>

          <div v-if="transactions.length === 0" class="py-8 text-center text-xs text-slate-500">
            Belum ada riwayat transaksi pembelian.
          </div>

          <div v-else class="divide-y divide-slate-800 text-xs">
            <div v-for="trx in transactions" :key="trx.id" class="py-3 flex items-center justify-between">
              <div>
                <p class="font-bold text-white">{{ trx.package?.name || 'Paket Token' }} ({{ trx.tokens }} Token)</p>
                <p class="text-xxs text-slate-400">Order ID: {{ trx.order_id }}</p>
              </div>
              <div class="text-right">
                <span class="font-bold text-emerald-400">Rp {{ Number(trx.amount).toLocaleString('id-ID') }}</span>
                <span class="block text-xxs font-extrabold uppercase text-amber-400">{{ trx.payment_status }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
