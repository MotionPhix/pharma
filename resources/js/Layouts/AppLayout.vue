<script setup>
import { ref } from 'vue';
import {Link} from '@inertiajs/vue3'
import AppLogo from '@/Components/AppLogo.vue'
import {Button} from '@/Components/ui/button'
import User from '@/Pages/Auth/Partials/User.vue';
import SearchPharmacyFancy from '@/Pages/Pharmacy/Partials/SearchPharmacyFancy.vue';
</script>

<template>
  <div class="app-layout">
    <header class="py-2 header">
      <nav class="navbar">

        <div class="logo">
          <Link as="button" class="flex items-center gap-2" href="/">
            <AppLogo class="h-8 text-white" />
            <strong class="font-serif text-2xl">
              {{ $page.props.appName }}
            </strong>
          </Link>
        </div>

        <span class="flex-1 sm:hidden"></span>

        <ul class="nav-links">
          <li>
            <SearchPharmacyFancy />
          </li>
        </ul>

        <div class="auth-buttons" v-if="$page.props.auth.user">
          <User />
        </div>

        <div class="auth-buttons" v-else>
          <Button as-child>
            <Link :href="route('login')" as="button">
              Login
            </Link>
          </Button>
        </div>
      </nav>
    </header>

    <main class="fixed inset-x-0 overflow-y-auto bottom-0 top-[3.2rem]">
      <slot></slot>

      <footer class="text-sm text-muted-foreground">
        <p>&copy; 2023 PharmTracker. All rights reserved.</p>
      </footer>
    </main>
  </div>
</template>

<style scoped>
.app-layout {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.header {
  @apply bg-primary text-primary-foreground;
  padding-left: 1rem;
  padding-right: 1rem;
}

.navbar {
  display: flex;
  @apply sm:justify-between gap-2;
  align-items: center;
}

.nav-links {
  list-style: none;
  display: flex;
  gap: 1rem;
}

.auth-buttons {
  display: flex;
  gap: 1rem;
}

footer {
  text-align: center;
  padding: 1rem;
  background-color: #f1f1f1;
}
</style>
