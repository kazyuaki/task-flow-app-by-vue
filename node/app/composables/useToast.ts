/**
 * トースト処理
 * @returns 
 */
export const useToast = () => {
  const toast = useState("toast", () => ({
    show: false,
    message: "",
    type: "success" as "success" | "error",
  }));
  const toastSequence = useState("toast.sequence", () => 0);

  const showToast = (
    message: string,
    type: "success" | "error" = "success",
  ) => {
    toastSequence.value += 1;
    const sequence = toastSequence.value;

    toast.value = {
      show: true,
      message,
      type,
    };

    setTimeout(() => {
      if (toastSequence.value === sequence) {
        toast.value.show = false;
      }
    }, 3000);
  };

  return {
    toast,
    showToast,
  };
};
