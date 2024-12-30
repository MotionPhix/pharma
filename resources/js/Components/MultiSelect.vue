<script setup>
  import { useI18n } from 'vue-i18n'
  import { useResizeObserver, useDebounceFn, useDebounce } from '@vueuse/core'
  import { ChevronDown, Check } from 'lucide-vue-next'
import { Command, CommandEmpty, CommandGroup, CommandInput, CommandItem, CommandList, CommandSeparator } from './ui/command'
import { Popover, PopoverContent, PopoverTrigger } from './ui/popover'
import { Button } from './ui/button'
import { Badge } from './ui/badge'
import { onMounted, ref } from 'vue'
import { Label } from 'radix-vue'

const model = defineModel();

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    options: {
        type: Array,
        default: () => [],
    },
    optionsUrl: {
        type: String,
        default: null,
    },
});

const selectedValues = ref(new Set(model.value));
const optionsList = ref([]);
const q = ref("");

onMounted(() => {
    updateOptionList("");
});

const filterFunction = (e) => {
    updateOptionList(e.target.value);
};

const updateOptionList = useDebounce(async (query) => {
    if (!props.optionsUrl) return;

    q.value = query;
    const { data } = await axios.get(props.optionsUrl, {
        params: { q: query },
    });
    optionsList.value = data;
});

const toggleSelection = (optionValue) => {
    if (selectedValues.value.has(optionValue)) {
        selectedValues.value.delete(optionValue);
        model.value = model.value.filter((value) => value !== optionValue);
    } else {
        selectedValues.value.add(optionValue);
        model.value.push(optionValue);
    }
};

const clearSelections = () => {
    selectedValues.value.clear();
};
</script>

<template>
    <div class="flex flex-col gap-2">
        <Label>{{ title }}</Label>
        <Popover>
            <PopoverTrigger as-child>
                <Button
                    variant="outline"
                    class="flex justify-between h-10 font-normal text-muted-foreground"
                >
                    <span v-if="selectedValues.size < 1">{{ title }}</span>
                    <template v-if="selectedValues.size > 0">
                        <Badge
                            variant="secondary"
                            class="px-1 font-normal rounded-sm lg:hidden"
                        >
                            {{ selectedValues.size }} selected
                        </Badge>
                        <div class="hidden space-x-1 lg:flex">
                            <Badge
                                v-if="selectedValues.size > 3"
                                variant="secondary"
                                class="px-1 font-normal rounded-sm"
                            >
                                {{ selectedValues.size }} selected
                            </Badge>
                            <template v-else>
                                <Badge
                                    v-for="option in optionsList.filter(
                                        (option) =>
                                            selectedValues.has(option.value)
                                    )"
                                    :key="option.value"
                                    variant="secondary"
                                    class="px-1 font-normal rounded-sm"
                                >
                                    {{ option.label }}
                                </Badge>
                            </template>
                        </div>
                    </template>
                    <ChevronDown class="w-4 h-4 ml-2" />
                </Button>
            </PopoverTrigger>
            <PopoverContent class="w-[200px] p-0" align="start">
                <Command
                    :should-filter="props.optionsUrl == null"
                    :filter-function="
                        props.optionsUrl
                            ? null
                            : (list, term) =>
                                  list.filter((i) =>
                                      i.label
                                          .toLowerCase()
                                          .includes(term.toLowerCase())
                                  )
                    "
                >
                    <CommandInput
                        :placeholder="title"
                        v-model="q"
                        @input="filterFunction"
                    />
                    <CommandList>
                        <CommandEmpty>
                            {{
                                optionsUrl && q == ""
                                    ? "Start Typing to search"
                                    : "No results found."
                            }}
                        </CommandEmpty>
                        <CommandGroup>
                            <CommandItem
                                v-for="option in optionsList"
                                :key="option.value"
                                :value="option"
                                @select="toggleSelection(option.value)"
                            >
                                <div
                                    :class="[
                                        'mr-2 flex h-4 w-4 items-center justify-center rounded-sm border border-primary',
                                        selectedValues.has(option.value)
                                            ? 'bg-primary text-primary-foreground'
                                            : 'opacity-50 [&_svg]:invisible',
                                    ]"
                                >
                                    <Check class="w-4 h-4" />
                                </div>
                                <span>{{ option.label }}</span>
                            </CommandItem>
                        </CommandGroup>
                        <template v-if="selectedValues.size > 0">
                            <CommandSeparator />
                            <CommandGroup>
                                <CommandItem
                                    class="justify-center text-center"
                                    @select="clearSelections"
                                >
                                    Clear Selection
                                </CommandItem>
                            </CommandGroup>
                        </template>
                    </CommandList>
                </Command>
            </PopoverContent>
        </Popover>
    </div>
</template>
