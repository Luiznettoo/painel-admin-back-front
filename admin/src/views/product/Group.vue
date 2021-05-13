<template>
    <article class="card">
        <div class="card-header">
            Grupo #{{ offset + 1 }}
            <div class="remove-me float-right" @click="remove"><i class="fa fa-trash"></i></div>
        </div>
        <hr>
        <div class="form-group row">
            <label :for="`gname${_uid}`" class="col-sm-2 col-form-label">Nome do grupo:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" :id="`name${_uid}`" v-model="name"/>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Opções:</label>
            <options-selector class="col-sm-10" :data="dataOptions" :value="value.options"
                              @input="changeOptions"></options-selector>
        </div>
    </article>
</template>

<script>
	import OptionsSelector from './OptionSelector'

	export default {
		name: "Group",
		components: {
			OptionsSelector
		},
		props: [
			'value',
			'offset'
		],
		data() {
			return {
				name: '',
				options: [],
				dataOptions: [],
			}
		},
		watch: {
			name() {
				this.sendInput();
			},
			colors() {
				this.sendInput();
			}
		},
		mounted() {
			_axios.get('option')
				.then((response) => {
					this.dataOptions = response.data;
				});
			if (this.value) {
				this.name = this.value.name;
				for (let option of this.value.options) {
					this.options.push(option.id);
                }

				this.sendInput();
			}
		},
		methods: {
			sendInput() {
				this.$emit('input', {
					name: this.name,
					options: this.options,
				});
			},
			remove() {
				this.$emit('remove', this.offset);
			},
			addColor() {
				this.colors.push({
					name: '',
					options: null
				});
			},
			removeOption(offset) {
				this.colors.splice(offset, 1);
			},
			changeOptions(options) {
				this.options = options;
				this.sendInput();
            }
		}
	}
</script>

<style scoped>

</style>