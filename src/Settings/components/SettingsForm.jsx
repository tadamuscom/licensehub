import apiFetch from '@wordpress/api-fetch';
import { useEffect, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { Button, ErrorMessage, FormGroup } from '@global';
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
		const stripeIntegration = lchb_settings.stripe_integration === 'true';
		const stripePublicKey = lchb_settings.stripe_public_key;
		const stripePrivateKey = lchb_settings.stripe_private_key;
		const stripeFluentCRM = lchb_settings.fluentcrm_integration === 'true';

		changeFormValue('stripeIntegration', stripeIntegration);
		changeFormValue('stripePublicKey', stripePublicKey);
		changeFormValue('stripePrivateKey', stripePrivateKey);
		changeFormValue('fluentCRMIntegration', stripeFluentCRM);
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
		} finally {
			setLoading(false);
		}
	};

	return (
		<form onSubmit={handleSubmit}>
			<Stripe formValues={formValues} changeFormValue={changeFormValue} />
			<FluentCRM formValues={formValues} changeFormValue={changeFormValue} />
			<FormGroup>
				<Button type="submit">
					{loading
						? __('Loading...', 'licensehub')
						: __('Save Settings', 'licensehub')}
				</Button>
				{error && <ErrorMessage>{error}</ErrorMessage>}
			</FormGroup>
		</form>
	);
};
