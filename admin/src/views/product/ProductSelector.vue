<template>
    <ul class="list-unstyled lista">
        <li v-for="(item, offset) in data" class="item">
            <div class="item-single">
                <div v-if="item.id !== null" class="wrapper">
                    <input type="checkbox" @input="toggleSelection(item.id)" :checked="value.indexOf(item.id) !== -1" />
                </div>
                <div class="name">{{ item.name }}</div>
            </div>
        </li>
    </ul>
</template>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<script>
	export default {
		name: "ProductSelector",
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
				showChilds: [],
				minhaClasse: false,
			}
		},
		methods: {
			toggleShowList(offset) {
				this.minhaClasse = true;

				this.showChilds[offset] = !this.showChilds[offset];

				this.$forceUpdate();
			},
			toggleSelection(id) {
				let offset = this.value.indexOf(id);

				if (offset !== -1) {
					let newVal = [];

					for (let i = 0; i < this.value.length; i++) {
						// console.log(this.value[i], offset);
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
    .lista {
        display: table;
        /*display: flex;*/
        /*flex-direction: column;*/
        /*flex-wrap: wrap;*/
        /*height: 500px;*/
        /*max-width:400px;*/
        /*display: grid;*/
        /*grid-template-columns: 1fr 1fr 1fr 1fr;*/
    }
    .item {
        float: left;
        width: 25%;
    }
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
