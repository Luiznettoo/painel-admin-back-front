<template>
	<div class="animated fadeIn">
		<b-row>
			<b-col md="12">
				<b-card>
					<FtpList></FtpList>
				</b-card>
			</b-col>
		</b-row>
	</div>
</template>

<script>
	import FtpList from './FtpList';
	
	export default {
		name: "Files",
		components: {
			FtpList
		}
	};
</script>

<style scoped>

</style>
