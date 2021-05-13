<template>
    <div v-if="validationErrors.length">
        <ul class="alert alert-danger list-unstyled">
            <li v-for="value in validationErrors">{{ value }}</li>
        </ul>
    </div>
</template>

<script>
    export default {
        name: 'ValidationErrors',
        props: ['errors'],
        computed: {
            validationErrors() {
                let errors = Object.values(this.errors);
                errors = errors.flat();

                return errors;
            }
        }
    };
</script>

<style scoped>
    .alert {
        padding: 0.40rem 1rem;
    }
</style>