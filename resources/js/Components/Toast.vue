<script setup>
import { onMounted, onUnmounted, reactive } from 'vue';

const props = defineProps({
  timeout: { type: Number, default: 3000 },
});

const state = reactive({
  show: false,
  message: '',
  type: 'success', // success | error | info
});

let timer;

function open(message, type = 'success') {
  state.message = message;
  state.type = type;
  state.show = true;
  clearTimeout(timer);
  timer = setTimeout(() => (state.show = false), props.timeout);
}

onUnmounted(() => clearTimeout(timer));

defineExpose({ open });
</script>

<template>
  <transition
    enter-active-class="transition ease-out duration-200"
    enter-from-class="opacity-0 translate-y-2"
    enter-to-class="opacity-100 translate-y-0"
    leave-active-class="transition ease-in duration-150"
    leave-from-class="opacity-100 translate-y-0"
    leave-to-class="opacity-0 translate-y-2"
  >
    <div v-if="state.show" class="fixed bottom-6 right-6 z-50">
      <div :class="[
        'px-4 py-3 rounded-lg shadow-xl text-white text-sm',
        state.type === 'success' && 'bg-emerald-600 shadow-emerald-400/30',
        state.type === 'error' && 'bg-rose-600 shadow-rose-400/30',
        state.type === 'info' && 'bg-sky-600 shadow-sky-400/30',
      ]">
        {{ state.message }}
      </div>
    </div>
  </transition>
</template>