import Vue from 'vue'
import Notifications from 'vue-notification'

Vue.use(Notifications)

//註冊傳送值所需要的格式
Vue.use({

	install: function (Vue) {
		//region -- 註冊特殊事件名稱 -------------------------
		Vue.mixin({
			methods: {
				/**
				 * notify
				 *
				 * @param {object} setting
				 * @return {object} date
				 */
				notify( setting ) {
					const notifyData = {
						type: 'success',
						text: setting.message
					}
					this.$notify(notifyData);
				},

				/**
				 * errorNotify
				 *
				 * @param {object} setting
				 * @return {object} date
				 */
				errorNotify( setting ) {
					let message = ''
					if (setting.errors !== undefined) {
						message = Object.values(setting.errors).pop()[0]
					} else {
						message = setting.message
					}

					this.$notify({
						type: 'error',
						text: message
					});
				}
			}
		});
	}
});
