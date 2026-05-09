import { computed, type Ref } from "vue";
import { sortTasks } from "~/utils/taskSort";
import type { SortKey, SortOrder, Task } from "~/types/task";

type UseTaskFilterProps = {
  tasks: Ref<Task[]>;
  keyword: Ref<string>;
  selectedStatus: Ref<string>;
  selectedCategory: Ref<string>;
  selectedPriority: Ref<string>;
  sortKey: Ref<SortKey>;
  sortOrder: Ref<SortOrder>;
};

/**
 * タスクのフィルタリングとソートを行うカスタムフック
 * @param props - フィルタリングとソートに必要なプロパティ
 * @returns フィルタリングされたタスクのリスト
 */
export const useTaskFilter = ({
  tasks,
  keyword,
  selectedStatus,
  selectedCategory,
  selectedPriority,
  sortKey,
  sortOrder,
}: UseTaskFilterProps) => {
  const filteredTasks = computed(() => {
    // フィルタリング
    const filtered = tasks.value.filter((task) => {
      const matchesKeyword =
        !keyword.value ||
        task.title.includes(keyword.value) ||
        task.description.includes(keyword.value);

      const matchesStatus =
        !selectedStatus.value || task.status === selectedStatus.value;

      const matchesCategory =
        !selectedCategory.value || task.category === selectedCategory.value;

      const matchesPriority =
        !selectedPriority.value || task.priority === selectedPriority.value;

      return (
        matchesKeyword && matchesStatus && matchesCategory && matchesPriority
      );
    });
    // ソート
    return sortTasks(filtered, sortKey.value, sortOrder.value);
  });

  return filteredTasks;
};
