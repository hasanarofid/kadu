<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { 
  User, 
  Search, 
  RotateCcw, 
  UserPlus, 
  ChevronRight,
  GitFork,
  Sparkles,
  ShieldCheck,
  CheckCircle2
} from '@lucide/vue';

const props = defineProps({
  focus_user: Object,
  tree: Object,
  all_users: Array
});

const selectedUserSearch = ref(props.focus_user?.id || '');

const focusUser = (userId) => {
  if (!userId) return;
  router.get(route('admin.pohon-jaringan'), { focus_id: userId }, { preserveState: true });
};

const resetFocus = () => {
  selectedUserSearch.value = '';
  router.get(route('admin.pohon-jaringan'));
};
</script>

<template>
  <Head title="Pohon Jaringan Binary - XSELLER" />

  <AdminLayout>
    <div class="space-y-6">
      
      <!-- Top Search & Focus Control Bar -->
      <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Search User Select -->
        <div class="relative flex-1 max-w-xl flex items-center gap-2">
          <div class="relative w-full">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
              <Search class="w-4 h-4" />
            </span>
            <select
              v-model="selectedUserSearch"
              @change="focusUser(selectedUserSearch)"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-8 py-2.5 text-xs font-semibold text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors appearance-none cursor-pointer"
            >
              <option v-for="u in all_users" :key="u.id" :value="u.id">
                {{ u.label }}
              </option>
            </select>
            <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400 text-xs">
              ▼
            </span>
          </div>
        </div>

        <!-- Reset Button -->
        <button 
          @click="resetFocus"
          class="px-4 py-2.5 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all flex items-center gap-2 shadow-sm shrink-0 cursor-pointer"
        >
          <RotateCcw class="w-3.5 h-3.5 text-slate-500" />
          <span>Reset Fokus ke Saya</span>
        </button>
      </div>

      <!-- Main Tree Container Card -->
      <div class="bg-white border border-slate-200/80 rounded-3xl p-6 md:p-8 shadow-sm space-y-6">
        
        <!-- Header Info -->
        <div class="border-b border-slate-100 pb-4 space-y-1">
          <div class="flex items-center gap-2">
            <span class="text-xl">🏛️</span>
            <h2 class="text-base font-extrabold text-slate-900 tracking-tight">Silsilah Pohon Jaringan (Genealogy Binary)</h2>
          </div>
          <p class="text-xs text-slate-500">Visualisasi struktur 2 kaki (Binary). Klik member untuk memfokuskan pohon jaringan, melihat detail, atau mendaftarkan member baru.</p>
          
          <div class="pt-2 flex items-center gap-1.5 text-xs text-slate-600 font-medium">
            <span class="text-slate-400">Navigasi:</span>
            <button @click="focusUser(tree?.id)" class="text-emerald-600 font-bold hover:underline">
              {{ tree?.name }} ({{ tree?.username }})
            </button>
          </div>
        </div>

        <!-- Binary Tree Visualization Board -->
        <div class="p-6 md:p-10 bg-slate-50/50 rounded-2xl border border-slate-100 overflow-x-auto min-w-[700px]">
          <div class="flex flex-col items-center space-y-8">
            
            <!-- LEVEL 1: Root Node (Focused Member) -->
            <div class="flex flex-col items-center relative">
              <div 
                @click="focusUser(tree?.id)"
                class="w-48 bg-white border-2 border-emerald-500 rounded-2xl p-3.5 shadow-md relative hover:scale-105 transition-all cursor-pointer space-y-2 text-center"
              >
                <!-- Status Dot -->
                <span class="absolute top-2.5 right-2.5 w-2 h-2 rounded-full bg-emerald-500"></span>

                <!-- Avatar Circle -->
                <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-600 border border-slate-200 mx-auto flex items-center justify-center">
                  <User class="w-5 h-5" />
                </div>

                <!-- Info -->
                <div>
                  <h4 class="text-xs font-black text-slate-800 truncate">{{ tree?.name }}</h4>
                  <p class="text-[10px] text-slate-400 font-medium truncate">{{ tree?.username }}</p>
                </div>

                <!-- Leg Counters Pill -->
                <div class="pt-1.5 border-t border-slate-100 flex items-center justify-center gap-3 text-[10px] font-bold text-slate-600">
                  <span>L : {{ tree?.left_count || 0 }}</span>
                  <span class="text-slate-300">|</span>
                  <span>R : {{ tree?.right_count || 0 }}</span>
                </div>
              </div>

              <!-- Connecting Line Down from Level 1 -->
              <div class="w-0.5 h-8 bg-slate-300"></div>
            </div>

            <!-- LEVEL 2 & 3 CONTAINER -->
            <div class="w-full relative">
              <!-- Horizontal Connecting Bar between Left and Right Children -->
              <div class="absolute top-0 left-1/4 right-1/4 h-0.5 bg-slate-300"></div>

              <div class="grid grid-cols-2 gap-8 relative pt-4">
                
                <!-- ================= LEFT BRANCH ================= -->
                <div class="flex flex-col items-center relative">
                  <!-- Vertical Connector down to Left Child -->
                  <div class="absolute -top-4 w-0.5 h-4 bg-slate-300"></div>

                  <!-- Level 2 Left Node (e.g. Budi Santoso) -->
                  <div v-if="tree?.left" class="flex flex-col items-center">
                    <div 
                      @click="focusUser(tree.left.id)"
                      class="w-44 bg-white border border-slate-200 rounded-2xl p-3 shadow-sm hover:border-indigo-400 hover:shadow-md transition-all cursor-pointer space-y-2 text-center relative"
                    >
                      <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-emerald-500"></span>
                      <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 border border-slate-200 mx-auto flex items-center justify-center">
                        <User class="w-4 h-4" />
                      </div>
                      <div>
                        <h4 class="text-xs font-bold text-slate-800 truncate">{{ tree.left.name }}</h4>
                        <p class="text-[10px] text-slate-400 font-medium truncate">{{ tree.left.username }}</p>
                      </div>
                      <div class="pt-1.5 border-t border-slate-100 flex items-center justify-center gap-2 text-[10px] font-bold text-slate-500">
                        <span>L : {{ tree.left.left_count || 0 }}</span>
                        <span class="text-slate-300">|</span>
                        <span>R : {{ tree.left.right_count || 0 }}</span>
                      </div>
                    </div>

                    <!-- Vertical Line down to Level 3 -->
                    <div class="w-0.5 h-8 bg-slate-300"></div>

                    <!-- Level 3 Left Children (Dewi & Eko) -->
                    <div class="relative w-full">
                      <div class="absolute top-0 left-1/4 right-1/4 h-0.5 bg-slate-300"></div>
                      <div class="grid grid-cols-2 gap-3 pt-4">
                        
                        <!-- Left-Left (Dewi Lestari) -->
                        <div class="flex flex-col items-center relative">
                          <div class="absolute -top-4 w-0.5 h-4 bg-slate-300"></div>
                          <div 
                            v-if="tree.left.left"
                            @click="focusUser(tree.left.left.id)"
                            class="w-36 bg-white border border-slate-200 rounded-2xl p-2.5 shadow-sm hover:border-indigo-400 transition-all cursor-pointer space-y-1.5 text-center relative"
                          >
                            <span class="absolute top-2 right-2 w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            <div class="w-7 h-7 rounded-full bg-slate-100 text-slate-600 mx-auto flex items-center justify-center">
                              <User class="w-3.5 h-3.5" />
                            </div>
                            <h5 class="text-[11px] font-bold text-slate-800 truncate">{{ tree.left.left.name }}</h5>
                            <p class="text-[9px] text-slate-400 truncate">{{ tree.left.left.username }}</p>
                            <div class="pt-1 border-t border-slate-100 flex items-center justify-center gap-1.5 text-[9px] font-bold text-slate-500">
                              <span>L : {{ tree.left.left.left_count || 0 }}</span>
                              <span>R : {{ tree.left.left.right_count || 0 }}</span>
                            </div>
                          </div>
                          <!-- Empty Left Slot -->
                          <div 
                            v-else
                            class="w-36 border-2 border-dashed border-slate-200 rounded-2xl p-3 text-center text-slate-400 hover:border-indigo-400 hover:text-indigo-600 transition-colors cursor-pointer space-y-1"
                          >
                            <UserPlus class="w-4 h-4 mx-auto text-slate-400" />
                            <span class="text-[9px] font-bold uppercase block">KIRI KOSONG</span>
                          </div>
                        </div>

                        <!-- Left-Right (Eko Prasetyo) -->
                        <div class="flex flex-col items-center relative">
                          <div class="absolute -top-4 w-0.5 h-4 bg-slate-300"></div>
                          <div 
                            v-if="tree.left.right"
                            @click="focusUser(tree.left.right.id)"
                            class="w-36 bg-white border border-slate-200 rounded-2xl p-2.5 shadow-sm hover:border-indigo-400 transition-all cursor-pointer space-y-1.5 text-center relative"
                          >
                            <span class="absolute top-2 right-2 w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            <div class="w-7 h-7 rounded-full bg-slate-100 text-slate-600 mx-auto flex items-center justify-center">
                              <User class="w-3.5 h-3.5" />
                            </div>
                            <h5 class="text-[11px] font-bold text-slate-800 truncate">{{ tree.left.right.name }}</h5>
                            <p class="text-[9px] text-slate-400 truncate">{{ tree.left.right.username }}</p>
                            <div class="pt-1 border-t border-slate-100 flex items-center justify-center gap-1.5 text-[9px] font-bold text-slate-500">
                              <span>L : {{ tree.left.right.left_count || 0 }}</span>
                              <span>R : {{ tree.left.right.right_count || 0 }}</span>
                            </div>
                          </div>
                          <!-- Empty Slot -->
                          <div 
                            v-else
                            class="w-36 border-2 border-dashed border-slate-200 rounded-2xl p-3 text-center text-slate-400 hover:border-indigo-400 hover:text-indigo-600 transition-colors cursor-pointer space-y-1"
                          >
                            <UserPlus class="w-4 h-4 mx-auto text-slate-400" />
                            <span class="text-[9px] font-bold uppercase block">KANAN KOSONG</span>
                          </div>
                        </div>

                      </div>
                    </div>

                  </div>

                  <!-- Empty Left Branch Node -->
                  <div v-else class="w-44 border-2 border-dashed border-slate-200 rounded-2xl p-4 text-center text-slate-400 hover:border-indigo-400 hover:text-indigo-600 transition-colors cursor-pointer space-y-1">
                    <UserPlus class="w-5 h-5 mx-auto text-slate-400" />
                    <span class="text-xs font-bold uppercase block">KIRI KOSONG</span>
                  </div>
                </div>

                <!-- ================= RIGHT BRANCH ================= -->
                <div class="flex flex-col items-center relative">
                  <!-- Vertical Connector down to Right Child -->
                  <div class="absolute -top-4 w-0.5 h-4 bg-slate-300"></div>

                  <!-- Level 2 Right Node (e.g. Siti Rahma) -->
                  <div v-if="tree?.right" class="flex flex-col items-center">
                    <div 
                      @click="focusUser(tree.right.id)"
                      class="w-44 bg-white border border-slate-200 rounded-2xl p-3 shadow-sm hover:border-indigo-400 hover:shadow-md transition-all cursor-pointer space-y-2 text-center relative"
                    >
                      <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-emerald-500"></span>
                      <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 border border-slate-200 mx-auto flex items-center justify-center">
                        <User class="w-4 h-4" />
                      </div>
                      <div>
                        <h4 class="text-xs font-bold text-slate-800 truncate">{{ tree.right.name }}</h4>
                        <p class="text-[10px] text-slate-400 font-medium truncate">{{ tree.right.username }}</p>
                      </div>
                      <div class="pt-1.5 border-t border-slate-100 flex items-center justify-center gap-2 text-[10px] font-bold text-slate-500">
                        <span>L : {{ tree.right.left_count || 0 }}</span>
                        <span class="text-slate-300">|</span>
                        <span>R : {{ tree.right.right_count || 0 }}</span>
                      </div>
                    </div>

                    <!-- Vertical Line down to Level 3 -->
                    <div class="w-0.5 h-8 bg-slate-300"></div>

                    <!-- Level 3 Right Children (Fajar & Empty Kanan Kosong Slot) -->
                    <div class="relative w-full">
                      <div class="absolute top-0 left-1/4 right-1/4 h-0.5 bg-slate-300"></div>
                      <div class="grid grid-cols-2 gap-3 pt-4">
                        
                        <!-- Right-Left (Fajar Hidayat) -->
                        <div class="flex flex-col items-center relative">
                          <div class="absolute -top-4 w-0.5 h-4 bg-slate-300"></div>
                          <div 
                            v-if="tree.right.left"
                            @click="focusUser(tree.right.left.id)"
                            class="w-36 bg-white border border-slate-200 rounded-2xl p-2.5 shadow-sm hover:border-indigo-400 transition-all cursor-pointer space-y-1.5 text-center relative"
                          >
                            <span class="absolute top-2 right-2 w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            <div class="w-7 h-7 rounded-full bg-slate-100 text-slate-600 mx-auto flex items-center justify-center">
                              <User class="w-3.5 h-3.5" />
                            </div>
                            <h5 class="text-[11px] font-bold text-slate-800 truncate">{{ tree.right.left.name }}</h5>
                            <p class="text-[9px] text-slate-400 truncate">{{ tree.right.left.username }}</p>
                            <div class="pt-1 border-t border-slate-100 flex items-center justify-center gap-1.5 text-[9px] font-bold text-slate-500">
                              <span>L : {{ tree.right.left.left_count || 0 }}</span>
                              <span>R : {{ tree.right.left.right_count || 0 }}</span>
                            </div>
                          </div>
                          <!-- Empty Slot -->
                          <div 
                            v-else
                            class="w-36 border-2 border-dashed border-slate-200 rounded-2xl p-3 text-center text-slate-400 hover:border-indigo-400 hover:text-indigo-600 transition-colors cursor-pointer space-y-1"
                          >
                            <UserPlus class="w-4 h-4 mx-auto text-slate-400" />
                            <span class="text-[9px] font-bold uppercase block">KIRI KOSONG</span>
                          </div>
                        </div>

                        <!-- Right-Right (KANAN KOSONG - Dashed Box matching Screenshot) -->
                        <div class="flex flex-col items-center relative">
                          <div class="absolute -top-4 w-0.5 h-4 bg-slate-300"></div>
                          <div 
                            v-if="tree.right.right"
                            @click="focusUser(tree.right.right.id)"
                            class="w-36 bg-white border border-slate-200 rounded-2xl p-2.5 shadow-sm hover:border-indigo-400 transition-all cursor-pointer space-y-1.5 text-center relative"
                          >
                            <span class="absolute top-2 right-2 w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            <div class="w-7 h-7 rounded-full bg-slate-100 text-slate-600 mx-auto flex items-center justify-center">
                              <User class="w-3.5 h-3.5" />
                            </div>
                            <h5 class="text-[11px] font-bold text-slate-800 truncate">{{ tree.right.right.name }}</h5>
                            <p class="text-[9px] text-slate-400 truncate">{{ tree.right.right.username }}</p>
                            <div class="pt-1 border-t border-slate-100 flex items-center justify-center gap-1.5 text-[9px] font-bold text-slate-500">
                              <span>L : {{ tree.right.right.left_count || 0 }}</span>
                              <span>R : {{ tree.right.right.right_count || 0 }}</span>
                            </div>
                          </div>
                          <!-- Dashed Slot: KANAN KOSONG -->
                          <div 
                            v-else
                            class="w-36 border-2 border-dashed border-slate-300 bg-white/60 rounded-2xl p-3 text-center text-slate-400 hover:border-emerald-500 hover:text-emerald-600 transition-all cursor-pointer space-y-1 shadow-sm"
                          >
                            <UserPlus class="w-4 h-4 mx-auto text-slate-400" />
                            <span class="text-[9px] font-bold uppercase block tracking-wider">KANAN KOSONG</span>
                          </div>
                        </div>

                      </div>
                    </div>

                  </div>

                  <!-- Empty Right Branch Node -->
                  <div v-else class="w-44 border-2 border-dashed border-slate-300 bg-white/60 rounded-2xl p-4 text-center text-slate-400 hover:border-emerald-500 hover:text-emerald-600 transition-all cursor-pointer space-y-1 shadow-sm">
                    <UserPlus class="w-5 h-5 mx-auto text-slate-400" />
                    <span class="text-xs font-bold uppercase block tracking-wider">KANAN KOSONG</span>
                  </div>
                </div>

              </div>
            </div>

          </div>
        </div>

      </div>

    </div>
  </AdminLayout>
</template>
