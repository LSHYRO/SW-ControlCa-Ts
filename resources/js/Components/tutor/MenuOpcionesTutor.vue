<script setup>
import Dropdown from '../Dropdown.vue';
const props = defineProps({
    align: {
        type: String,
        default: 'right',
    },
    width: {
        type: String,
        default: '48',
    },
    contentClasses: {
        type: Array,
        default: () => ['py-1', 'bg-white'],
    },
});

let open = ref(false);

const closeOnEscape = (e) => {
    if (open.value && e.key === 'Escape') {
        open.value = false;
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));

const widthClass = computed(() => {
    return {
        '48': 'w-48',
    }[props.width.toString()];
});

const alignmentClasses = computed(() => {
    if (props.align === 'left') {
        return 'origin-top-left left-0';
    }

    if (props.align === 'right') {
        return 'origin-top-right right-0';
    }

    return 'origin-top';
});
</script>
<template>
    <dropdown>
        <button @click="actualizarProfesor(profesor.idPersonal)">Actualizar</button>
        <button @click="eliminarProfesor(profesor.idPersonal)">Eliminar</button>
    </dropdown>
</template>