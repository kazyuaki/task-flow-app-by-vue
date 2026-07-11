<!-- プロフィール編集モーダル -->
<script setup lang="ts">
type Props = {
  isOpen: boolean;
  name: string;
  email: string;
};

const props = defineProps<Props>();

const emit = defineEmits<{
  close: [];
  save: [{ name: string; email: string }];
}>();

const form = reactive({
  name: "",
  email: "",
});

watch(
  () => props.isOpen,
  (isOpen) => {
    if (!isOpen) {
      return;
    }

    form.name = props.name;
    form.email = props.email;
  },
  {
    immediate: true,
  },
);

const handleClose = () => {
  emit("close");
};

const handleSubmit = () => {
  emit("save", {
    name: form.name,
    email: form.email,
  });
};
</script>

<template>
  <Teleport to="body">
    <div v-if="props.isOpen" class="modal-overlay" @click.self="handleClose">
      <section
        class="modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="profile-modal-title"
      >
        <h2 id="profile-modal-title">プロフィール編集</h2>

        <form class="modal-form" @submit.prevent="handleSubmit">
          <label class="form-item">
            <span>名前</span>
            <input v-model="form.name" type="text" autocomplete="name" />
          </label>

          <label class="form-item">
            <span>メールアドレス</span>
            <input v-model="form.email" type="email" autocomplete="email" />
          </label>

          <div class="modal-buttons">
            <button type="button" class="cancel-button" @click="handleClose">
              キャンセル
            </button>

            <button type="submit" class="save-button">保存する</button>
          </div>
        </form>
      </section>
    </div>
  </Teleport>
</template>

<style scoped>
.modal-overlay {
  position: fixed;
  inset: 0;
  z-index: 1000;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgb(0 0 0 / 40%);
}

.modal {
  width: 100%;
  max-width: 480px;
  padding: 24px;
  border-radius: 12px;
  background: #fff;
}

.modal h2 {
  margin: 0 0 24px;
  font-size: 24px;
}

.modal-form {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.form-item {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.form-item span {
  font-size: 14px;
  font-weight: 600;
  color: #666;
}

.form-item input {
  width: 100%;
  padding: 12px;
  border: 1px solid #d5d9dd;
  border-radius: 8px;
  font-size: 16px;
  box-sizing: border-box;
}

.form-item input:focus {
  outline: none;
  border-color: #2f755d;
  box-shadow: 0 0 0 3px rgb(47 117 93 / 12%);
}

.modal-buttons {
  display: flex;
  gap: 12px;
  margin-top: 8px;
}

.modal-buttons button {
  flex: 1;
  padding: 12px;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
}

.cancel-button {
  background: #f3f4f6;
  color: #333;
}

.save-button {
  background: #2f755d;
  color: #fff;
}

.save-button:hover {
  opacity: 0.9;
}

@media (max-width: 640px) {
  .modal {
    padding: 20px;
  }

  .modal-buttons {
    flex-direction: column;
  }
}
</style>
