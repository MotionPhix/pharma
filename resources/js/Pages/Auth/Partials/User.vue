<script setup lang="ts">
import {
  Avatar, AvatarFallback, AvatarImage
} from '@/Components/ui/avatar';
import { Button } from '@/Components/ui/button'
import { Link } from '@inertiajs/vue3';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuGroup,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuShortcut,
  DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu'
import {
  CreditCard,
  LifeBuoy,
  LogOut,
  Settings,
  User,
  Users,
} from 'lucide-vue-next'
</script>

<template>
  <DropdownMenu :modal="false">
    <DropdownMenuTrigger as-child>
      <Button
        variant="outline"
        as-child class="rounded-full" size="icon">
        <Avatar>
          <AvatarImage
            :src="$page.props.auth.user.avatar"
            :alt="$page.props.auth.user.name" />
          <AvatarFallback>CN</AvatarFallback>
        </Avatar>
      </Button>
    </DropdownMenuTrigger>

    <DropdownMenuContent align="end" class="w-48">
      <DropdownMenuLabel>
        {{ $page.props.auth.user.name }}
      </DropdownMenuLabel>

      <DropdownMenuSeparator />

      <DropdownMenuGroup>
        <DropdownMenuItem as-child>
          <Link
            class="w-full" as="button"
            :href="route('profile.edit')">
            <User class="w-4 h-4 mr-2" />
            <span>Profile</span>
            <DropdownMenuShortcut>⇧⌘P</DropdownMenuShortcut>
          </Link>
        </DropdownMenuItem>

        <DropdownMenuItem>
          <CreditCard class="w-4 h-4 mr-2" />
          <span>Billing</span>
          <DropdownMenuShortcut>⌘B</DropdownMenuShortcut>
        </DropdownMenuItem>

        <DropdownMenuItem>
          <Settings class="w-4 h-4 mr-2" />
          <span>Settings</span>
          <DropdownMenuShortcut>⌘S</DropdownMenuShortcut>
        </DropdownMenuItem>
      </DropdownMenuGroup>

      <DropdownMenuSeparator />

      <DropdownMenuItem as-child>
        <Link
          as="button" class="w-full"
          :href="route('pharmacies.favorites.index')">
          <Users class="w-4 h-4 mr-2" />
          <span>Favorites</span>
        </Link>
      </DropdownMenuItem>

      <DropdownMenuItem>
        <LifeBuoy class="w-4 h-4 mr-2" />
        <span>Support</span>
      </DropdownMenuItem>

      <DropdownMenuSeparator />

      <DropdownMenuItem as-child>
        <Link
          as="button" class="w-full"
          :href="route('logout')" method="post">
          <LogOut class="w-4 h-4 mr-2" />
          <span>Log out</span>
          <DropdownMenuShortcut>⇧⌘Q</DropdownMenuShortcut>
        </Link>
      </DropdownMenuItem>
    </DropdownMenuContent>
  </DropdownMenu>
</template>
