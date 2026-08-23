<script setup>
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { CheckCircle2, XCircle, Clock, Eye, User, Mail, Phone, Hash, FileImage, MessageSquare, RefreshCw } from '@lucide/vue';

const props = defineProps({
    orders: { type: Array, default: () => [] },
});

const selectedOrder = ref(null);
const rejectNotes = ref('');
const verifyNotes = ref('');
const activeFilter = ref('all');
const proofModal = ref(null);

const statusConfig = {
    pending:  { label: 'Pending',     color: 'text-amber-700 bg-amber-100 border-amber-300' },
    verified: { label: 'Terverifikasi', color: 'text-green-700 bg-green-100 border-green-300' },
    rejected: { label: 'Ditolak',     color: 'text-red-700 bg-red-100 border-red-300' },
};

const filteredOrders = () => {
    if (activeFilter.value === 'all') return props.orders;
    return props.orders.filter(o => o.status === activeFilter.value);
};

const counts = {
    all:      () => props.orders.length,
    pending:  () => props.orders.filter(o => o.status === 'pending').length,
    verified: () => props.orders.filter(o => o.status === 'verified').length,
    rejected: () => props.orders.filter(o => o.status === 'rejected').length,
};

const formatRp = (val) => 'Rp ' + Number(val).toLocaleString('id-ID');

function doVerify(order) {
    if (!confirm(`Verifikasi order dari ${order.name}? ${order.voucher_qty} Voucher akan diterbitkan otomatis.`)) return;
    const form = useForm({ admin_notes: verifyNotes.value });
    form.post(route('admin.payment-orders.verify', { order: order.id }), {
        onSuccess: () => { selectedOrder.value = null; verifyNotes.value = ''; },
    });
}

function doReject(order) {
    if (!rejectNotes.value.trim()) { alert('Catatan penolakan wajib diisi!'); return; }
    if (!confirm(`Tolak order dari ${order.name}?`)) return;
    const form = useForm({ admin_notes: rejectNotes.value });
    form.post(route('admin.payment-orders.reject', { order: order.id }), {
        onSuccess: () => { selectedOrder.value = null; rejectNotes.value = ''; },
    });
}
</script>

<template>
    <Head title="Kelola Order Pembayaran - Admin" />

    <div class="min-h-screen bg-[#f5f0e8] p-4 sm:p-6">
        <div class="max-w-6xl mx-auto space-y-6">

            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-black text-[#5c2c24]">📦 Order Pembayaran Manual</h1>
                    <p class="text-sm text-[#9d7c64]">Verifikasi bukti transfer & terbitkan Voucher Aktivasi</p>
                </div>
                <button @click="router.reload()" class="flex items-center gap-1.5 text-xs text-[#5c2c24] font-semibold border border-[#e09d49] px-3 py-1.5 rounded-lg hover:bg-[#fffaf2] transition-all">
                    <RefreshCw class="w-3.5 h-3.5" /> Refresh
                </button>
            </div>

            <!-- Filter Tabs -->
            <div class="flex gap-2 flex-wrap">
                <button
                    v-for="tab in ['all', 'pending', 'verified', 'rejected']"
                    :key="tab"
                    @click="activeFilter = tab"
                    :class="['px-4 py-1.5 rounded-full text-xs font-bold border transition-all', activeFilter === tab ? 'bg-[#5c2c24] text-white border-[#5c2c24]' : 'bg-white text-[#5c2c24] border-[#e09d49]/50 hover:border-[#5c2c24]']"
                >
                    {{ { all: 'Semua', pending: 'Pending', verified: 'Terverifikasi', rejected: 'Ditolak' }[tab] }}
                    <span class="ml-1 opacity-70">({{ counts[tab]() }})</span>
                </button>
            </div>

            <!-- Orders Table -->
            <div class="bg-white rounded-2xl border border-[#e09d49]/30 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-[#5c2c24] text-white">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold">#</th>
                                <th class="px-4 py-3 text-left text-xs font-bold">Pemesan</th>
                                <th class="px-4 py-3 text-left text-xs font-bold">Voucher</th>
                                <th class="px-4 py-3 text-left text-xs font-bold">Total</th>
                                <th class="px-4 py-3 text-left text-xs font-bold">Bukti</th>
                                <th class="px-4 py-3 text-left text-xs font-bold">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-bold">Tgl. Order</th>
                                <th class="px-4 py-3 text-left text-xs font-bold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e09d49]/10">
                            <tr v-if="filteredOrders().length === 0">
                                <td colspan="8" class="text-center py-10 text-[#9d7c64]">Tidak ada data</td>
                            </tr>
                            <tr
                                v-for="order in filteredOrders()"
                                :key="order.id"
                                class="hover:bg-[#fffaf2] transition-colors"
                            >
                                <td class="px-4 py-3 text-[#9d7c64] text-xs font-mono">#{{ order.id }}</td>
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-[#5c2c24]">{{ order.name }}</div>
                                    <div class="text-[#9d7c64] text-xs">{{ order.email }}</div>
                                    <div v-if="order.phone" class="text-[#9d7c64] text-xs">{{ order.phone }}</div>
                                </td>
                                <td class="px-4 py-3 font-black text-[#5c2c24] text-center">{{ order.voucher_qty }}</td>
                                <td class="px-4 py-3 font-bold text-[#e98318]">{{ formatRp(order.amount) }}</td>
                                <td class="px-4 py-3">
                                    <button
                                        v-if="order.transfer_proof"
                                        @click="proofModal = order.transfer_proof"
                                        class="flex items-center gap-1 text-xs text-[#e98318] font-semibold hover:underline"
                                    >
                                        <Eye class="w-3.5 h-3.5" /> Lihat
                                    </button>
                                    <span v-else class="text-[#9d7c64] text-xs">—</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span :class="['text-xs font-bold px-2 py-1 rounded-full border', statusConfig[order.status]?.color]">
                                        {{ statusConfig[order.status]?.label }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-[#9d7c64] text-xs">{{ order.created_at }}</td>
                                <td class="px-4 py-3">
                                    <button
                                        v-if="order.status === 'pending'"
                                        @click="selectedOrder = order"
                                        class="text-xs bg-[#5c2c24] text-white px-3 py-1 rounded-lg font-bold hover:bg-[#e98318] transition-colors"
                                    >
                                        Proses
                                    </button>
                                    <div v-else class="text-[#9d7c64] text-xs">
                                        {{ order.verified_at }}<br>
                                        <span class="text-[10px]">by {{ order.verified_by }}</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Proses Order -->
        <div v-if="selectedOrder" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" @click.self="selectedOrder = null">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 space-y-4">
                <h3 class="font-black text-[#5c2c24] text-base">Proses Order #{{ selectedOrder.id }}</h3>

                <!-- Info Order -->
                <div class="bg-[#fffaf2] rounded-xl p-4 space-y-1.5 text-sm">
                    <div class="flex justify-between"><span class="text-[#9d7c64]">Nama</span><span class="font-semibold">{{ selectedOrder.name }}</span></div>
                    <div class="flex justify-between"><span class="text-[#9d7c64]">Email</span><span class="font-semibold">{{ selectedOrder.email }}</span></div>
                    <div class="flex justify-between"><span class="text-[#9d7c64]">Voucher</span><span class="font-black text-[#5c2c24]">{{ selectedOrder.voucher_qty }} Voucher</span></div>
                    <div class="flex justify-between"><span class="text-[#9d7c64]">Total</span><span class="font-black text-[#e98318]">{{ formatRp(selectedOrder.amount) }}</span></div>
                </div>

                <!-- Bukti Transfer Preview -->
                <div v-if="selectedOrder.transfer_proof" class="rounded-xl overflow-hidden border border-[#e09d49]/40 max-h-48">
                    <img :src="selectedOrder.transfer_proof" alt="Bukti Transfer" class="w-full object-contain max-h-48 bg-gray-50" />
                </div>

                <!-- Catatan -->
                <div>
                    <label class="text-xs font-bold text-[#5c2c24] mb-1 block">Catatan (opsional untuk verifikasi, wajib untuk tolak)</label>
                    <textarea
                        v-model="rejectNotes"
                        rows="3"
                        placeholder="Catatan admin..."
                        class="w-full rounded-xl border border-[#e09d49]/60 text-sm p-3 text-[#5c2c24] focus:outline-none focus:ring-2 focus:ring-[#e98318]/20 focus:border-[#e98318] resize-none"
                    ></textarea>
                </div>

                <!-- Actions -->
                <div class="grid grid-cols-2 gap-3">
                    <button
                        @click="doReject(selectedOrder)"
                        class="h-10 rounded-full bg-red-600 text-white font-bold text-sm flex items-center justify-center gap-1.5 hover:bg-red-700 transition-colors"
                    >
                        <XCircle class="w-4 h-4" /> Tolak
                    </button>
                    <button
                        @click="doVerify(selectedOrder)"
                        class="h-10 rounded-full bg-gradient-to-r from-[#1a6b3e] to-[#27ae60] text-white font-bold text-sm flex items-center justify-center gap-1.5 hover:brightness-105 transition-all"
                    >
                        <CheckCircle2 class="w-4 h-4" /> Verifikasi
                    </button>
                </div>

                <button @click="selectedOrder = null" class="w-full text-xs text-[#9d7c64] hover:text-[#5c2c24] transition-colors">Batal</button>
            </div>
        </div>

        <!-- Modal Bukti Transfer -->
        <div v-if="proofModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4" @click.self="proofModal = null">
            <div class="max-w-xl w-full bg-white rounded-2xl overflow-hidden shadow-2xl">
                <div class="flex items-center justify-between p-4 border-b border-gray-100">
                    <span class="font-bold text-[#5c2c24]">Bukti Transfer</span>
                    <button @click="proofModal = null" class="text-gray-400 hover:text-gray-700 font-black text-xl">&times;</button>
                </div>
                <img :src="proofModal" alt="Bukti Transfer" class="w-full object-contain max-h-[70vh] bg-gray-50" />
            </div>
        </div>
    </div>
</template>
