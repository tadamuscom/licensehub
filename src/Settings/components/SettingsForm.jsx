import apiFetch from '@wordpress/api-fetch';
import { useEffect, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { Button } from '@global';
import { FluentCRM } from '@settings/components/FluentCRM';
import { Stripe } from '@settings/components/Stripe';

export const SettingsForm = ({ onSubmit }) => {
	const [formValues, setFormValues] = useState({
		stripeIntegration: false,
		stripePublicKey: '',
		stripePrivateKey: '',
		fluentCRMIntegration: false,
	});
	const [loading, setLoading] = useState(false);
	const [error, setError] = useState(false);

	const changeFormValue = (key, value) => {
		setFormValues((prev) => ({
			...prev,
			[key]: value,
		}));
	};

	useEffect(() => {
		changeFormValue(
			'stripeIntegration',
			lchb_settings.stripe_integration === 'true',
		);
		changeFormValue('stripePublicKey', lchb_settings.stripe_public_key);
		changeFormValue('stripePrivateKey', lchb_settings.stripe_private_key);
		changeFormValue(
			'fluentCRMIntegration',
			lchb_settings.fluentcrm_integration === 'true',
		);
	}, []);

	const handleSubmit = async (e) => {
		e.preventDefault();

		setLoading(true);

		try {
			const res = await apiFetch({
				path: '/tadamus/lchb/v1/general-settings',
				method: 'POST',
				data: JSON.stringify({
					nonce: /*lchb_settings.nonce*/ 'nonce',
					...formValues,
				}),
			});
		} catch (e) {
			setError(e.message);
			setLoading(false);
			return;
		}

		setLoading(false);
	};

	return (
		<form onSubmit={handleSubmit} id="tada-add-product-form">
			<Stripe formValues={formValues} changeFormValue={changeFormValue} />
			<FluentCRM formValues={formValues} changeFormValue={changeFormValue} />
			<Button type="submit">
				{loading
					? __('Loading...', 'licensehub')
					: __('Save Settings', 'licensehub')}
			</Button>
			{error && <p className="tada-error-message">{error}</p>}
		</form>
	);
};
