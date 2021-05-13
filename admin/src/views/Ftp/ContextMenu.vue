<template>
	<section v-show="opened" v-click-outside="close" @contextmenu.prevent="">
		<ul class="list-unstyled">
			<li v-for="(option,index) in options" v-if="!option.disabled" @click="chose(index)" :class="{def: option.default}">{{ option.name }}</li>
		</ul>
	</section>
</template>

<script>
	import vClickOutside from 'vue-click-outside';
	
	export default {
		name: "ContextMenu",
		props: ['options'],
		directives: {
			clickOutside: vClickOutside
		},
		data() {
			return {
				opened: false,
				relativeParent: null,
				offsetDiff: {
                    x: 0,
                    y: 0,
				}
			}
		},
		mounted() {
			this.$el.style.left = 0;
			this.$el.style.top = 0;
		},
		methods: {
			open(e) {
				this.opened = true;
                this.$el.style.left = 0;
                this.$el.style.top = 0;

				this.$nextTick(function() {
					let vh = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
					
					if (e.clientY + this.$el.clientHeight > vh) {
						this.$el.style.left = `${e.clientX - this.$el.getBoundingClientRect().x}px`;
						this.$el.style.top = `${e.clientY - this.$el.getBoundingClientRect().y - this.$el.clientHeight}px`;
					}
					else {
						this.$el.style.left = `${e.clientX - this.$el.getBoundingClientRect().x}px`;
						this.$el.style.top = `${e.clientY - this.$el.getBoundingClientRect().y}px`;
					}
				});
			},
			close() {
				this.opened = false;
			},
			chose(opt) {
				this.$emit('chose', opt);
				this.close();
			}
		}
	}
</script>

<style scoped>
	section {
		position: absolute;
		background-color: #ececec;
		border: 1px solid #b5b5b5;
		border-radius: 2px;
		padding: 4px;
		z-index: 10;
	}
	
	ul {
		margin-bottom: 0;
	}
	
	li {
		padding: 4px;
		min-width: 200px;
		font-size: 14px;
		user-select: none;
	}
	
	li:hover {
		background-color: #d2d2d2;
	}
	
	li.def {
		font-weight: bold;
	}
</style>
