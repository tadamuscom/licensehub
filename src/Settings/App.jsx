import apiFetch from '@wordpress/api-fetch';
import { Header, HeadingTwo } from '@global';
import { SettingsForm } from '@settings/components/SettingsForm';

export const App = () => {
	const submit = (e) => {
		e.preventDefault();

		const btn = document.querySelector(
			'#' + e.target.id + ' input[type="submit"]',
		);
		btn.disabled = true;
		btn.value = 'Loading...';

		const formData = new FormData(e.target);
		const stripeIntegration = formData.get('tada-stripe-integration');
		const stripePublicKey = formData.get('tada-public-key');
		const stripePrivateKey = formData.get('tada-private-key');
		const fluentCRMIntegration = formData.get('tada-fluentcrm-integration');

		let go = true;

		if (stripeIntegration) {
			if (stripePublicKey.length < 1) {
				go = false;
			}

			if (stripePrivateKey.length < 1) {
				go = false;
			}
		}

		const status = document.getElementById('tada-status');

		if (!go) {
			btn.value = 'Save Settings';
			btn.disabled = false;

			status.style.color = 'red';
			status.innerText = 'Please fix the errors above ❌';

			if (status.classList.contains('tada-hidden')) {
				status.classList.remove('tada-hidden');
			}

			return;
		}

		apiFetch({
			path: '/tadamus/lchb/v1/general-settings',
			method: 'POST',
			data: {
				nonce: lchb_settings.nonce,
				stripe_integration: stripeIntegration,
				stripe_public_key: stripePublicKey,
				stripe_private_key: stripePrivateKey,
				fluentcrm_integration: fluentCRMIntegration,
			},
		}).then((result) => {
			btn.value = 'Save Settings';
			btn.disabled = false;
			status.innerText = result.data.message;

			if (result.success) {
				status.style.color = 'green';
			} else {
				status.style.color = 'red';
				status.innerText = status.innerText + ' ❌';
			}

			if (status.classList.contains('tada-hidden')) {
				status.classList.remove('tada-hidden');
			}
		});
	};

	return (
		<div>
			<Header pageTitle="Settings" />
			<HeadingTwo label="Settings" />
			<SettingsForm onSubmit={submit} />
		</div>
	);
};
