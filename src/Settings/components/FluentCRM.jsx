import { __ } from '@wordpress/i18n';
import { CheckBox, FormGroup } from '@global';

export const FluentCRM = ({ formValues, changeFormValue }) => {
	return (
		<FormGroup>
			<CheckBox
				label={__('FluentCRM Integration', 'licensehub')}
				id="tada-fluentcrm-integration"
				name="tada-fluentcrm-integration"
				checked={formValues.fluentCRMIntegration}
				onClick={() =>
					changeFormValue(
						'fluentCRMIntegration',
						!formValues.fluentCRMIntegration,
					)
				}
			/>
		</FormGroup>
	);
};
