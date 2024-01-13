<script>
export default {
    props: {
        total: {
            type: Number,
            required: true
        },
        limit: {
            type: Number,
            default: 10
        }
    },
    computed: {
        totalPages() {
            return Math.ceil(this.total / this.limit);
        },
        currentPage: {
            get() {
                return this.$page.props.currentPage;
            },
            set(value) {
                this.$inertia.visit(route('your-route'), {
                    data: {
                        currentPage: value
                    },
                    preserveState: true,
                    preserveScroll: true,
                });
            }
        }
    }
};
</script>
<template>
    <div class="flex justify-between">
        <div>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                :disabled="currentPage === 1" @click="currentPage--">
                Previous
            </button>
        </div>
        <div>
            <span>{{ currentPage }} of {{ totalPages }}</span>
        </div>
        <div>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                :disabled="currentPage === totalPages" @click="currentPage++">
                Next
            </button>
        </div>
    </div>
</template>
   
