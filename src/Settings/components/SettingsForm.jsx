import { useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { Button, FormGroup } from '@global';
import { FormStatus } from '@global/components/form/FormStatus';
import { useForms } from '@global/hooks/useForms';
import { FluentCRM } from '@settings/components/FluentCRM';
import { Stripe } from '@settings/components/Stripe';

export const SettingsForm = ({ onSubmit }) => {
	const { loading, result, formData, changeFormValue, post } = useForms({
		stripeIntegration: false,
		stripePublicKey: '',
		stripePrivateKey: '',
		fluentCRMIntegration: false,
	});

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

		await post('/tadamus/lchb/v1/general-settings', lchb_settings.nonce);
	};

	return (
		<form onSubmit={handleSubmit}>
			<Stripe
				formData={formData}
				changeFormValue={changeFormValue}
				result={result}
			/>
			<FluentCRM formData={formData} changeFormValue={changeFormValue} />
			<FormGroup>
				<Button type="submit" loading={loading}>
					{loading
						? __('Loading...', 'licensehub')
						: __('Save Settings', 'licensehub')}
				</Button>
				<FormStatus status={result} />
			</FormGroup>
		</form>
	);
};
