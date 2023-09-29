export default async function ({ redirect, store }) {
  const loggedIn = await store.getters.isAuthenticated

  if (!loggedIn) {
    redirect('/login')
  }
}
