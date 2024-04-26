import { __ } from '@wordpress/i18n';
import { CheckBox, FormGroup, Input, Label } from '@global';

export const Stripe = ({ formValues, changeFormValue }) => {
	return (
		<>
			<FormGroup>
				<CheckBox
					label={__('Stripe Integration', 'licensehub')}
					id="tada-stripe-integration"
					name="tada-stripe-integration"
					checked={formValues.stripeIntegration}
					onClick={() =>
						changeFormValue('stripeIntegration', !formValues.stripeIntegration)
					}
				/>
			</FormGroup>
			{formValues.stripeIntegration && (
				<div
					id="lchb-stripe-credentials"
					style={{
						marginTop: '25px',
						marginLeft: '10px',
					}}>
					<FormGroup>
						<Label htmlFor="lchb-public-key">
							{__('Public Key', 'licensehub')}
						</Label>
						<Input
							id="tada-public-key"
							name="tada-public-key"
							value={formValues.stripePublicKey}
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
							id="tada-private-key"
							name="tada-private-key"
							value={formValues.stripePrivateKey}
							onChange={(e) =>
								changeFormValue('stripePrivateKey', e.target.value)
							}
						/>
					</FormGroup>
				</div>
			)}
		</>
	);
};
