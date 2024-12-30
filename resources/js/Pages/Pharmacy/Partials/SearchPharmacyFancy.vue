<script setup lang="ts">
import {
  CommandDialog,
  CommandEmpty,
  CommandGroup,
  CommandInput,
  CommandItem,
  CommandList,
  CommandSeparator,
} from '@/Components/ui/command'
import { DialogDescription, DialogTitle } from '@/Components/ui/dialog'

import { useMagicKeys } from '@vueuse/core'
import { SearchIcon } from 'lucide-vue-next'
import { ref, watch } from 'vue'

const open = ref(false)

const { Meta_J, Ctrl_J } = useMagicKeys({
  passive: false,
  onEventFired(e) {
    if (e.key === 'j' && (e.metaKey || e.ctrlKey))
      e.preventDefault()
  },
})

watch([Meta_J, Ctrl_J], (v) => {
  if (v[0] || v[1])
    handleOpenChange()
})

function handleOpenChange() {
  open.value = !open.value
}
</script>

<template>
  <div>
    <div
      @click="open = true"
      class="w-40 text-muted-foreground">

      <kbd
        class="pointer-events-none flex h-7 select-none items-center py-4 gap-1 rounded-md border bg-muted px-1.5 sm:font-mono font-medium w-full text-muted-foreground opacity-100">

        <SearchIcon class="w-5 h-5" />
        <span class="hidden sm:inline-flex">Press ⌘J</span>
        <span class="inline-flex sm:hidden">Start typing</span>

      </kbd>

    </div>

    <CommandDialog position="top" v-model:open="open">
      <DialogTitle />
      <DialogDescription />
      <CommandInput placeholder="Type a command or search..." />
      <CommandList>
        <CommandEmpty>No results found.</CommandEmpty>
        <CommandGroup heading="Suggestions">
          <CommandItem value="calendar">
            Calendar
          </CommandItem>
          <CommandItem value="search-emoji">
            Search Emoji
          </CommandItem>
          <CommandItem value="calculator">
            Calculator
          </CommandItem>
        </CommandGroup>
        <CommandSeparator />
        <CommandGroup heading="Settings">
          <CommandItem value="profile">
            Profile
          </CommandItem>
          <CommandItem value="billing">
            Billing
          </CommandItem>
          <CommandItem value="settings">
            Settings
          </CommandItem>
        </CommandGroup>
      </CommandList>
    </CommandDialog>
  </div>
</template>
