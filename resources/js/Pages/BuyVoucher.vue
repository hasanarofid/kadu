<script setup>
import { ref, computed } from 'vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { Upload, CreditCard, User, Mail, Phone, Hash, Building2, CheckCircle2, ArrowRight, AlertCircle } from '@lucide/vue';

const props = defineProps({
    voucher_price: { type: Number, default: 500000 },
    bank_info: { type: Object, default: () => ({}) },
});

const proofPreview = ref(null);
const dragOver = ref(false);

const form = useForm({
    name: '',
    email: '',
    phone: '',
    voucher_qty: 1,
    transfer_proof: null,
});

const totalAmount = computed(() => props.voucher_price * form.voucher_qty);

const formatRp = (val) => 'Rp ' + Number(val).toLocaleString('id-ID');

function handleFile(file) {
    if (!file) return;
    form.transfer_proof = file;
    const reader = new FileReader();
    reader.onload = (e) => (proofPreview.value = e.target.result);
    reader.readAsDataURL(file);
}

function onFileChange(e) { handleFile(e.target.files[0]); }
function onDrop(e) {
    dragOver.value = false;
    handleFile(e.dataTransfer.files[0]);
}

function submit() {
    form.post(route('payment.store'), { forceFormData: true });
}
</script>

<template>
    <Head title="Beli Voucher Aktivasi - Mitra Syiar Baitullah" />

    <div class="min-h-screen bg-gradient-to-br from-[#f5f0e8] via-[#fffaf2] to-[#f0e8d8] flex flex-col">
        <!-- Header -->
        <header class="w-full py-4 px-6 flex items-center justify-between border-b border-[#e09d49]/30 bg-white/80 backdrop-blur-sm">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#e98318] to-[#5c2c24] flex items-center justify-center text-white text-xs font-black">M</div>
                <span class="font-black text-[#5c2c24] text-sm tracking-tight">MITRA SYIAR BAITULLAH</span>
            </div>
            <Link :href="route('login')" class="text-xs font-semibold text-[#e98318] hover:underline">Sudah punya akun? Login</Link>
        </header>

        <main class="flex-1 flex items-start justify-center py-8 px-4">
            <div class="w-full max-w-2xl space-y-5">

                <!-- Title -->
                <div class="text-center">
                    <h1 class="text-2xl font-black text-[#5c2c24]">🎟️ Beli Voucher Aktivasi</h1>
                    <p class="text-sm text-[#9d7c64] mt-1">Isi form & upload bukti transfer — Admin akan memverifikasi dalam 1×24 jam</p>
                </div>

                <!-- Info Rekening -->
                <div class="bg-gradient-to-r from-[#5c2c24] to-[#e98318] rounded-2xl p-5 text-white shadow-lg">
                    <div class="flex items-center gap-2 mb-3">
                        <Building2 class="w-5 h-5" />
                        <span class="font-bold text-sm">Rekening Tujuan Transfer</span>
                    </div>
                    <div class="bg-white/15 rounded-xl p-4 space-y-1.5">
                        <div class="flex justify-between items-center">
                            <span class="text-white/75 text-xs">Bank</span>
                            <span class="font-black text-base">{{ bank_info.bank_name }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-white/75 text-xs">No. Rekening</span>
                            <span class="font-black text-base tracking-widest">{{ bank_info.account_no }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-white/75 text-xs">Atas Nama</span>
                            <span class="font-bold text-sm">{{ bank_info.account_name }}</span>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center gap-2 bg-white/10 rounded-lg px-3 py-2">
                        <AlertCircle class="w-4 h-4 shrink-0" />
                        <p class="text-xs text-white/90">Harga per Voucher: <strong>{{ formatRp(voucher_price) }}</strong> — Transfer sesuai jumlah pesanan!</p>
                    </div>
                </div>

                <!-- Form -->
                <form @submit.prevent="submit" class="bg-white rounded-2xl shadow-lg border border-[#e09d49]/30 p-6 space-y-4">
                    <h2 class="font-black text-[#5c2c24] text-base">📋 Data Pemesan</h2>

                    <!-- Nama -->
                    <div>
                        <label class="flex items-center gap-1.5 text-[11px] font-extrabold text-[#5c2c24] mb-1">
                            <User class="w-3.5 h-3.5 text-[#e98318]" /> Nama Lengkap
                        </label>
                        <div class="relative">
                            <User class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#9d7c64]" />
                            <input
                                v-model="form.name"
                                type="text"
                                required
                                placeholder="Nama sesuai KTP"
                                class="w-full h-10 pl-10 pr-3 rounded-xl border border-[#e09d49]/60 text-sm text-[#5c2c24] placeholder-[#9d7c64]/50 focus:outline-none focus:ring-2 focus:ring-[#e98318]/20 focus:border-[#e98318] transition-all"
                            />
                        </div>
                        <p v-if="form.errors.name" class="text-red-500 text-xs mt-0.5">{{ form.errors.name }}</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Email -->
                        <div>
                            <label class="flex items-center gap-1.5 text-[11px] font-extrabold text-[#5c2c24] mb-1">
                                <Mail class="w-3.5 h-3.5 text-[#e98318]" /> Email Aktif
                            </label>
                            <div class="relative">
                                <Mail class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#9d7c64]" />
                                <input
                                    v-model="form.email"
                                    type="email"
                                    required
                                    placeholder="email@anda.com"
                                    class="w-full h-10 pl-10 pr-3 rounded-xl border border-[#e09d49]/60 text-sm text-[#5c2c24] placeholder-[#9d7c64]/50 focus:outline-none focus:ring-2 focus:ring-[#e98318]/20 focus:border-[#e98318] transition-all"
                                />
                            </div>
                            <p v-if="form.errors.email" class="text-red-500 text-xs mt-0.5">{{ form.errors.email }}</p>
                        </div>

                        <!-- No. HP -->
                        <div>
                            <label class="flex items-center gap-1.5 text-[11px] font-extrabold text-[#5c2c24] mb-1">
                                <Phone class="w-3.5 h-3.5 text-[#e98318]" /> No. WhatsApp
                            </label>
                            <div class="relative">
                                <Phone class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#9d7c64]" />
                                <input
                                    v-model="form.phone"
                                    type="tel"
                                    required
                                    placeholder="08xxxxxxxxxx"
                                    class="w-full h-10 pl-10 pr-3 rounded-xl border border-[#e09d49]/60 text-sm text-[#5c2c24] placeholder-[#9d7c64]/50 focus:outline-none focus:ring-2 focus:ring-[#e98318]/20 focus:border-[#e98318] transition-all"
                                />
                            </div>
                            <p v-if="form.errors.phone" class="text-red-500 text-xs mt-0.5">{{ form.errors.phone }}</p>
                        </div>
                    </div>

                    <!-- Jumlah Voucher -->
                    <div>
                        <label class="flex items-center gap-1.5 text-[11px] font-extrabold text-[#5c2c24] mb-1">
                            <Hash class="w-3.5 h-3.5 text-[#e98318]" /> Jumlah Voucher
                        </label>
                        <div class="flex items-center gap-3">
                            <div class="relative flex-1">
                                <Hash class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#9d7c64]" />
                                <input
                                    v-model.number="form.voucher_qty"
                                    type="number"
                                    min="1"
                                    max="20"
                                    required
                                    class="w-full h-10 pl-10 pr-3 rounded-xl border border-[#e09d49]/60 text-sm text-[#5c2c24] focus:outline-none focus:ring-2 focus:ring-[#e98318]/20 focus:border-[#e98318] transition-all"
                                />
                            </div>
                            <div class="bg-[#fffaf2] border-2 border-[#e98318] rounded-xl px-4 py-2 text-center min-w-[140px]">
                                <div class="text-[10px] text-[#9d7c64] font-semibold">TOTAL TRANSFER</div>
                                <div class="text-base font-black text-[#5c2c24]">{{ formatRp(totalAmount) }}</div>
                            </div>
                        </div>
                        <p v-if="form.errors.voucher_qty" class="text-red-500 text-xs mt-0.5">{{ form.errors.voucher_qty }}</p>
                    </div>

                    <!-- Upload Bukti Transfer -->
                    <div>
                        <label class="flex items-center gap-1.5 text-[11px] font-extrabold text-[#5c2c24] mb-1">
                            <Upload class="w-3.5 h-3.5 text-[#e98318]" /> Bukti Transfer
                        </label>
                        <div
                            class="relative border-2 border-dashed rounded-xl transition-all cursor-pointer overflow-hidden"
                            :class="dragOver ? 'border-[#e98318] bg-[#fffaf2]' : 'border-[#e09d49]/50 hover:border-[#e98318] hover:bg-[#fffaf2]/50'"
                            @dragover.prevent="dragOver = true"
                            @dragleave="dragOver = false"
                            @drop.prevent="onDrop"
                            @click="$refs.fileInput.click()"
                        >
                            <input ref="fileInput" type="file" accept="image/*,.pdf" class="hidden" @change="onFileChange" />

                            <div v-if="!proofPreview" class="py-8 text-center">
                                <Upload class="w-8 h-8 mx-auto text-[#9d7c64] mb-2" />
                                <p class="text-sm font-semibold text-[#5c2c24]">Klik atau Drag & Drop di sini</p>
                                <p class="text-xs text-[#9d7c64] mt-1">JPG, PNG, PDF — Maks. 5MB</p>
                            </div>

                            <div v-else class="relative">
                                <img :src="proofPreview" alt="Preview bukti transfer" class="w-full max-h-48 object-contain bg-gray-50" />
                                <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                    <p class="text-white text-xs font-bold">Klik untuk ganti</p>
                                </div>
                            </div>
                        </div>
                        <p v-if="form.errors.transfer_proof" class="text-red-500 text-xs mt-0.5">{{ form.errors.transfer_proof }}</p>
                    </div>

                    <!-- Submit -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full h-12 rounded-full bg-gradient-to-r from-[#e98318] via-[#e09d49] to-[#5c2c24] text-white font-black text-sm tracking-wide shadow-lg shadow-[#e98318]/25 flex items-center justify-center gap-2 hover:brightness-105 active:scale-[0.99] transition-all disabled:opacity-50"
                    >
                        <span>{{ form.processing ? 'Mengirim...' : 'Kirim Order Pembelian' }}</span>
                        <ArrowRight class="w-4 h-4" />
                    </button>

                    <p class="text-center text-[10px] text-[#9d7c64]">
                        Setelah verifikasi admin, Voucher akan otomatis masuk ke akun Anda
                    </p>
                </form>
            </div>
        </main>
    </div>
</template>
