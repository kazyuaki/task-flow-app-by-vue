<script setup lang="ts">
const { fetchUser, isAuthenticated } = useAuth()

await fetchUser({
  force: true,
  clearAuthenticatedOnFail: true,
})

if (isAuthenticated.value) {
  await navigateTo('/tasks')
} else {
  await navigateTo('/login')
}
</script>