<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Wallet, Users, Award, TrendingUp, Sparkles, QrCode, ArrowUpRight, ArrowRight } from '@lucide/vue';
</script>

<template>
    <Head title="Dashboard - Mitra Syiar Baitullah" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-black leading-tight text-[#5c2c24] uppercase tracking-wide">
                        Dashboard Mitra Syiar
                    </h2>
                    <p class="text-xs font-medium text-[#9d7c64]">
                        Selamat datang kembali, {{ $page.props.auth.user.name }}. Bersama Menjadi Tamu Allah.
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 text-xs font-extrabold bg-[#e98318]/15 text-[#e98318] border border-[#e09d49]/40 rounded-full">
                        DP Join Rp 500.000 (Aktif)
                    </span>
                </div>
            </div>
        </template>

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Hero Gradient Summary Card -->
            <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-r from-[#2e395d] via-[#5c2c24] to-[#e98318] text-white shadow-xl relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-64 h-64 rounded-full bg-[#e09d49]/20 blur-2xl pointer-events-none"></div>

                <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                    <div class="lg:col-span-8 space-y-3">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur border border-white/20 text-xs font-bold text-[#e09d49]">
                            <Sparkles class="w-3.5 h-3.5" />
                            <span>Program Syiar Multi-Tier & Ujroh Sponsor</span>
                        </div>
                        <h3 class="text-2xl sm:text-3xl font-black tracking-tight leading-snug">
                            Ujroh Sponsor Rp 250.000 / Jamaah Baru
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-200 leading-relaxed max-w-2xl font-medium">
                            Sponsori 2 Mitra sudah Balik Modal! Dapatkan alokasi komisi team multi-tier hingga 12 Generasi dan Reward Syiar Prestasi hingga Rp 1.000.000.000.
                        </p>
                    </div>

                    <div class="lg:col-span-4 bg-white/10 backdrop-blur-md p-5 rounded-2xl border border-white/20 text-center space-y-2">
                        <span class="text-[10px] font-bold tracking-wider uppercase text-[#e09d49] block">KODE REFERRAL MITRA</span>
                        <div class="p-2.5 bg-black/20 rounded-xl text-lg font-black tracking-widest text-white border border-white/10 font-mono">
                            {{ $page.props.auth.user.team_code || 'SYIARBAITULLAH' }}
                        </div>
                        <p class="text-[11px] text-slate-300 font-medium">Bagikan tautan pendaftaran untuk mengajak mitra baru</p>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Card 1: Total Ujroh Sponsor -->
                <div class="p-5 rounded-2xl bg-white border border-[#e09d49]/40 shadow-sm space-y-2 relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-extrabold text-[#9d7c64] uppercase tracking-wider">Ujroh Sponsor</span>
                        <div class="p-2 rounded-xl bg-[#e98318]/15 text-[#e98318]">
                            <Wallet class="w-5 h-5" />
                        </div>
                    </div>
                    <p class="text-2xl font-black text-[#5c2c24]">Rp 500.000</p>
                    <div class="text-[11px] font-medium text-emerald-600 flex items-center gap-1">
                        <TrendingUp class="w-3.5 h-3.5" />
                        <span>2 Mitra Terdaftar (Balik Modal)</span>
                    </div>
                </div>

                <!-- Card 2: Komisi Team -->
                <div class="p-5 rounded-2xl bg-white border border-[#e09d49]/40 shadow-sm space-y-2 relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-extrabold text-[#9d7c64] uppercase tracking-wider">Komisi Team (Gen 1-12)</span>
                        <div class="p-2 rounded-xl bg-[#2e395d]/10 text-[#2e395d]">
                            <Users class="w-5 h-5" />
                        </div>
                    </div>
                    <p class="text-2xl font-black text-[#2e395d]">Rp 150.000</p>
                    <div class="text-[11px] font-medium text-[#9d7c64]">
                        <span>Terdistribusi dari 5 Team Aktif</span>
                    </div>
                </div>

                <!-- Card 3: Akumulasi Mitra -->
                <div class="p-5 rounded-2xl bg-white border border-[#e09d49]/40 shadow-sm space-y-2 relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-extrabold text-[#9d7c64] uppercase tracking-wider">Total Mitra Team</span>
                        <div class="p-2 rounded-xl bg-[#e09d49]/20 text-[#e98318]">
                            <QrCode class="w-5 h-5" />
                        </div>
                    </div>
                    <p class="text-2xl font-black text-[#5c2c24]">12 Mitra</p>
                    <div class="text-[11px] font-medium text-[#e98318]">
                        <span>Target berikutnya: 100 Mitra</span>
                    </div>
                </div>

                <!-- Card 4: Syiar Prestasi Reward -->
                <div class="p-5 rounded-2xl bg-white border border-[#e09d49]/40 shadow-sm space-y-2 relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-extrabold text-[#9d7c64] uppercase tracking-wider">Reward Prestasi</span>
                        <div class="p-2 rounded-xl bg-[#9ec5ca]/30 text-[#2e395d]">
                            <Award class="w-5 h-5" />
                        </div>
                    </div>
                    <p class="text-2xl font-black text-[#5c2c24]">Rp 500.000</p>
                    <div class="text-[11px] font-semibold text-emerald-600">
                        <span>Pencapaian 100 Mitra (Klaim Siap)</span>
                    </div>
                </div>
            </div>

            <!-- Table Multi-Tier Komisi Overview -->
            <div class="bg-white border border-[#e09d49]/40 rounded-3xl p-6 shadow-sm space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-[#e09d49]/20 pb-4">
                    <div>
                        <h4 class="text-base font-black text-[#5c2c24] uppercase tracking-wide">
                            Struktur Komisi Team (Generasi 1 - 12)
                        </h4>
                        <p class="text-xs font-semibold text-[#9d7c64]">
                            Potensi pendapatan dari pertumbuhan anggota jaringan syiar
                        </p>
                    </div>
                    <span class="text-xs font-bold text-[#e98318] italic">Syiar Baitullah PRD 2026</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs font-medium text-[#5c2c24]">
                        <thead>
                            <tr class="bg-[#f8efdf] text-[#5c2c24] font-black uppercase text-[11px]">
                                <th class="p-3 rounded-l-xl">Level Team</th>
                                <th class="p-3">Jumlah Mitra/Akun</th>
                                <th class="p-3">Komisi / Mitra</th>
                                <th class="p-3 rounded-r-xl text-right">Total Komisi Potensial</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e09d49]/20">
                            <tr>
                                <td class="p-3 font-bold text-[#e98318]">Team 1</td>
                                <td class="p-3">5 Mitra</td>
                                <td class="p-3 font-semibold">Rp 5.000</td>
                                <td class="p-3 text-right font-black text-[#5c2c24]">Rp 25.000</td>
                            </tr>
                            <tr>
                                <td class="p-3 font-bold text-[#e98318]">Team 2</td>
                                <td class="p-3">25 Mitra</td>
                                <td class="p-3 font-semibold">Rp 5.000</td>
                                <td class="p-3 text-right font-black text-[#5c2c24]">Rp 125.000</td>
                            </tr>
                            <tr>
                                <td class="p-3 font-bold text-[#e98318]">Team 3</td>
                                <td class="p-3">125 Mitra</td>
                                <td class="p-3 font-semibold">Rp 5.000</td>
                                <td class="p-3 text-right font-black text-[#5c2c24]">Rp 625.000</td>
                            </tr>
                            <tr>
                                <td class="p-3 font-bold text-[#e98318]">Team 4</td>
                                <td class="p-3">625 Mitra</td>
                                <td class="p-3 font-semibold">Rp 5.000</td>
                                <td class="p-3 text-right font-black text-[#5c2c24]">Rp 3.125.000</td>
                            </tr>
                            <tr class="bg-[#fffaf2]">
                                <td class="p-3 font-bold text-[#e98318]">Team 5 s/d 12</td>
                                <td class="p-3">3.125 s/d 244.140.625 Mitra</td>
                                <td class="p-3 font-semibold">Rp 1.000 - Rp 5.000</td>
                                <td class="p-3 text-right font-black text-[#e98318]">Hingga Rp 244 Miliar+</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

