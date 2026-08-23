<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { CheckCircle2, Clock, XCircle, Copy } from '@lucide/vue';
import { ref } from 'vue';

const props = defineProps({
    order: { type: Object, required: true },
});

const copied = ref(false);

const statusConfig = {
    pending:  { icon: Clock,         color: 'text-amber-600',  bg: 'bg-amber-50 border-amber-200',   label: 'Menunggu Verifikasi', desc: 'Order Anda sedang dalam antrian verifikasi Admin. Mohon tunggu maks. 1×24 jam.' },
    verified: { icon: CheckCircle2,  color: 'text-green-600',  bg: 'bg-green-50 border-green-200',   label: 'Terverifikasi ✅',     desc: 'Pembayaran Anda telah diverifikasi. Voucher sudah aktif di akun Anda!' },
    rejected: { icon: XCircle,       color: 'text-red-600',    bg: 'bg-red-50 border-red-200',       label: 'Ditolak ❌',           desc: 'Order Anda ditolak. Silakan hubungi Admin untuk informasi lebih lanjut.' },
};

const cfg = statusConfig[props.order.status] ?? statusConfig.pending;

const formatRp = (val) => 'Rp ' + Number(val).toLocaleString('id-ID');

function copyLink() {
    navigator.clipboard.writeText(window.location.href);
    copied.value = true;
    setTimeout(() => (copied.value = false), 2000);
}
</script>

<template>
    <Head title="Status Order - Mitra Syiar Baitullah" />

    <div class="min-h-screen bg-gradient-to-br from-[#f5f0e8] via-[#fffaf2] to-[#f0e8d8] flex flex-col">
        <!-- Header -->
        <header class="w-full py-4 px-6 flex items-center justify-between border-b border-[#e09d49]/30 bg-white/80 backdrop-blur-sm">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#e98318] to-[#5c2c24] flex items-center justify-center text-white text-xs font-black">M</div>
                <span class="font-black text-[#5c2c24] text-sm tracking-tight">MITRA SYIAR BAITULLAH</span>
            </div>
        </header>

        <main class="flex-1 flex items-center justify-center py-10 px-4">
            <div class="w-full max-w-md space-y-5">

                <!-- Status Card -->
                <div :class="['rounded-2xl border-2 p-6 text-center shadow-md', cfg.bg]">
                    <component :is="cfg.icon" :class="['w-14 h-14 mx-auto mb-3', cfg.color]" />
                    <h1 :class="['text-xl font-black mb-1', cfg.color]">{{ cfg.label }}</h1>
                    <p class="text-sm text-gray-600">{{ cfg.desc }}</p>
                </div>

                <!-- Detail Order -->
                <div class="bg-white rounded-2xl border border-[#e09d49]/30 shadow-md p-5 space-y-3">
                    <h2 class="font-black text-[#5c2c24] text-sm">📋 Detail Order</h2>

                    <div class="divide-y divide-[#e09d49]/20 text-sm">
                        <div class="flex justify-between py-2">
                            <span class="text-[#9d7c64]">Nama</span>
                            <span class="font-semibold text-[#5c2c24]">{{ order.name }}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-[#9d7c64]">Email</span>
                            <span class="font-semibold text-[#5c2c24]">{{ order.email }}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-[#9d7c64]">Jumlah Voucher</span>
                            <span class="font-bold text-[#5c2c24]">{{ order.voucher_qty }} Voucher</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-[#9d7c64]">Total</span>
                            <span class="font-black text-[#e98318]">{{ formatRp(order.amount) }}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-[#9d7c64]">Tgl. Order</span>
                            <span class="font-semibold text-[#5c2c24]">{{ order.created_at }}</span>
                        </div>
                        <div v-if="order.verified_at" class="flex justify-between py-2">
                            <span class="text-[#9d7c64]">Tgl. Verifikasi</span>
                            <span class="font-semibold text-green-600">{{ order.verified_at }}</span>
                        </div>
                        <div v-if="order.admin_notes" class="py-2">
                            <span class="text-[#9d7c64] block mb-1">Catatan Admin</span>
                            <span class="font-semibold text-[#5c2c24] block bg-gray-50 rounded-lg p-2 text-xs">{{ order.admin_notes }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="space-y-3">
                    <button
                        @click="copyLink"
                        class="w-full h-11 rounded-full border-2 border-[#e09d49] text-[#5c2c24] font-bold text-sm flex items-center justify-center gap-2 hover:bg-[#fffaf2] transition-all"
                    >
                        <Copy class="w-4 h-4" />
                        {{ copied ? 'Link Disalin!' : 'Salin Link Status ini' }}
                    </button>

                    <div class="text-center">
                        <Link :href="route('payment.create')" class="text-xs text-[#e98318] font-semibold hover:underline">
                            ← Beli Voucher Lagi
                        </Link>
                        <span class="mx-2 text-[#9d7c64]">|</span>
                        <Link :href="route('login')" class="text-xs text-[#e98318] font-semibold hover:underline">
                            Login ke Dashboard
                        </Link>
                    </div>
                </div>

                <p class="text-center text-[10px] text-[#9d7c64]">
                    Simpan link halaman ini untuk mengecek status order sewaktu-waktu.
                </p>
            </div>
        </main>
    </div>
</template>
