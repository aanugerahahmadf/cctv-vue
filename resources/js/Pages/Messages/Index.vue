<script setup>
import { ref, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';

const conversations = ref([]);
const selectedUser = ref(null);
const messages = ref([]);
const newMessage = ref('');
const loading = ref(false);
const sending = ref(false);
let pollTimer = null;

const fetchConversations = async () => {
  try {
    const res = await fetch('/messages/conversations');
    const data = await res.json();
    conversations.value = data.conversations || [];
    if (!selectedUser.value && conversations.value.length > 0) {
      selectConversation(conversations.value[0].user);
    }
  } catch (e) {
    console.error(e);
  }
};

const fetchMessages = async (userId) => {
  if (!userId) return;
  loading.value = true;
  try {
    const res = await fetch(`/messages/${userId}`);
    const data = await res.json();
    messages.value = data.messages || [];
    scrollToBottom();
  } catch (e) {
    console.error(e);
  } finally {
    loading.value = false;
  }
};

const selectConversation = async (user) => {
  selectedUser.value = user;
  await fetchMessages(user.id);
};

const sendMessage = async () => {
  if (!newMessage.value.trim() || !selectedUser.value) return;
  sending.value = true;
  try {
    const res = await fetch('/messages', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
      body: JSON.stringify({
        receiver_id: selectedUser.value.id,
        subject: 'User Message',
        message: newMessage.value.trim(),
      }),
    });
    if (res.ok) {
      newMessage.value = '';
      await fetchMessages(selectedUser.value.id);
    }
  } catch (e) {
    console.error(e);
  } finally {
    sending.value = false;
  }
};

const scrollToBottom = () => {
  requestAnimationFrame(() => {
    const el = document.getElementById('chat-scroll');
    if (el) el.scrollTop = el.scrollHeight;
  });
};

onMounted(async () => {
  await fetchConversations();
  // Poll messages every 5s
  pollTimer = setInterval(() => {
    if (selectedUser.value) fetchMessages(selectedUser.value.id);
  }, 5000);
});
</script>

<template>
  <UserLayout :user="$page.props.auth.user">
    <Head title="Chat - Kilang Pertamina Internasional" />

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Chat</h1>

      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 grid grid-cols-1 md:grid-cols-3 overflow-hidden">
        <!-- Conversations -->
        <div class="border-b md:border-b-0 md:border-r border-gray-200 dark:border-gray-700">
          <div class="p-4 font-semibold text-gray-900 dark:text-white">Percakapan</div>
          <div class="max-h-[60vh] overflow-y-auto">
            <button
              v-for="conv in conversations"
              :key="conv.user.id"
              @click="selectConversation(conv.user)"
              class="w-full text-left px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center gap-3"
              :class="{ 'bg-gray-50 dark:bg-gray-700': selectedUser?.id === conv.user.id }"
            >
              <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-medium">
                {{ conv.user.name?.charAt(0)?.toUpperCase() || 'U' }}
              </div>
              <div>
                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ conv.user.name }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[180px]">
                  {{ conv.last_message?.message || 'Mulai percakapan' }}
                </div>
              </div>
            </button>
          </div>
        </div>

        <!-- Chat Panel -->
        <div class="md:col-span-2 flex flex-col h-[70vh]">
          <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-medium">
              {{ selectedUser?.name?.charAt(0)?.toUpperCase() || 'U' }}
            </div>
            <div class="font-semibold text-gray-900 dark:text-white">
              {{ selectedUser?.name || 'Pilih percakapan' }}
            </div>
          </div>

          <!-- Messages -->
          <div id="chat-scroll" class="flex-1 overflow-y-auto p-4 space-y-2 bg-gray-50 dark:bg-gray-900">
            <div v-if="loading" class="text-center text-sm text-gray-500 dark:text-gray-400">Memuat pesan...</div>
            <template v-else>
              <div
                v-for="msg in messages"
                :key="msg.id"
                class="max-w-[75%] p-3 rounded-lg"
                :class="[
                  msg.sender_id === $page.props.auth.user.id
                    ? 'ml-auto bg-blue-600 text-white'
                    : 'mr-auto bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700'
                ]"
              >
                <div class="text-sm whitespace-pre-wrap">{{ msg.message }}</div>
                <div class="mt-1 text-[10px] opacity-70">{{ new Date(msg.created_at).toLocaleString() }}</div>
              </div>
            </template>
          </div>

          <!-- Composer -->
          <div class="p-3 border-t border-gray-200 dark:border-gray-700 flex items-center gap-2">
            <input
              v-model="newMessage"
              @keydown.enter.prevent="sendMessage"
              type="text"
              placeholder="Tulis pesan..."
              class="flex-1 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
            <button
              @click="sendMessage"
              :disabled="sending || !newMessage.trim() || !selectedUser"
              class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg disabled:opacity-50"
            >
              Kirim
            </button>
          </div>
        </div>
      </div>
    </div>
  </UserLayout>
</template>
