<template>
    <ul class="list-unstyled">
        <li v-for="(item, offset) in data">
            <div class="item-single">
                <div v-if="item.id !== null" class="wrapper">
                    <input type="checkbox" @input="toggleSelection(item.id)" :checked="value.indexOf(item.id) !== -1" />
                </div>
                <div class="name">{{ item.name }}</div>
            </div>
        </li>
    </ul>
</template>

<script>
	export default {
		name: "CategorySelector",
		props: {
			data: {
				required: true
			},
			value: {
				type: Array
			}
		},
		data() {
			return {
				checked: [],

				minhaClasse: false,
			}
		},
		beforeMount() {
			for (let i = 0; i < this.length; i++) {
				this.checked.push(false);
			}
		},
        mounted() {
        },
		methods: {
			toggleShowList(offset) {
				this.minhaClasse = true;
			},
			toggleSelection(id) {
				let offset = this.value.indexOf(id);

				if (offset !== -1) {
					let newVal = [];

					for (let i = 0; i < this.value.length; i++) {
						if (i === offset) {
							continue;
						}

						newVal.push(this.value[i]);
					}

					this.$emit('input', newVal);
				}
				else {
					this.$emit('input', this.value.concat(id));
				}
			},
			onInput(value) {
				this.$emit('input', value);
			}
		}
	}
</script>

<style scoped>
    ul {
        padding-left: 20px;
    }

    .item-single, .box-wrapper {
        display: flex;
        align-content: center;
        align-items: center;
    }

    .fas.invis {
        visibility: hidden;
    }

    .wrapper {
        position: relative;
        margin-right: 8px;
    }

    .box-wrapper {
        position: absolute;
        min-width: 320px;
        top: 0;
        left: 100%;
        margin-left: 8px;

        z-index: 1;
    }

    .name {
        padding: 2px 8px;
    }

    .fas {
        cursor: pointer;
    }
</style>