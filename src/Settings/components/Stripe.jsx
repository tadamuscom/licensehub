import { __ } from '@wordpress/i18n';
import { CheckBox, FormGroup, Input, Label } from '@global';

export const Stripe = ({ formData, changeFormValue, result }) => {
	return (
		<>
			<FormGroup>
				<CheckBox
					label={__('Stripe Integration', 'licensehub')}
					id="tada-stripe-integration"
					name="tada-stripe-integration"
					checked={formData.stripeIntegration}
					onClick={() =>
						changeFormValue('stripeIntegration', !formData.stripeIntegration)
					}
				/>
			</FormGroup>
			{formData.stripeIntegration && (
				<>
					<FormGroup>
						<Label htmlFor="lchb-public-key">
							{__('Public Key', 'licensehub')}
						</Label>
						<Input
							id="lchb-stripe-public-key"
							type="text"
							value={formData.stripePublicKey}
							result={result}
							onChange={(e) =>
								changeFormValue('stripePublicKey', e.target.value)
							}
						/>
					</FormGroup>

					<FormGroup>
						<Label htmlFor="lchb-private-key">
							{__('Private Key', 'licensehub')}
						</Label>
						<Input
							type="password"
							id="lchb-stripe-private-key"
							value={formData.stripePrivateKey}
							autoComplete="off"
							result={result}
							onChange={(e) =>
								changeFormValue('stripePrivateKey', e.target.value)
							}
						/>
					</FormGroup>
				</>
			)}
		</>
	);
};
