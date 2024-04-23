import { CheckBox, FormGroup } from '@global';

export const FluentCRM = () => {
	return (
		<>
			<FormGroup>
				<CheckBox
					label="FluentCRM Integration"
					id="tada-fluentcrm-integration"
					name="tada-fluentcrm-integration"
					value={lchb_settings.fluentcrm_integration === 'true'}
				/>
			</FormGroup>
		</>
	);
};
