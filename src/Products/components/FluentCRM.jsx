import { __ } from '@wordpress/i18n';
import { FormGroup, Input, Label } from '@global';

export const FluentCRM = ({ result, formData, changeFormValue }) => {
	if (lchb_products.fluentcrm_integration === 'true') {
		return (
			<>
				<FormGroup>
					<Label htmlFor="lchb-fluentcrm-lists">
						{__('FluentCRM List IDs', 'licensehub')}
					</Label>
					<Input
						type="text"
						id="lchb-fluentcrm-lists"
						name="lchb-fluentcrm-lists"
						value={formData.fluentCRMLists}
						helper={__('List IDs separated by comma', 'licensehub')}
						result={result}
						onChange={(e) => changeFormValue('fluentCRMLists', e.target.value)}
					/>
				</FormGroup>
				<FormGroup>
					<Label htmlFor="lchb-fluentcrm-tags">
						{__('FluentCRM Tag IDs', 'licensehub')}
					</Label>
					<Input
						type="text"
						id="lchb-fluentcrm-tags"
						name="lchb-fluentcrm-tags"
						value={formData.fluentCRMTags}
						helper={__('Tag IDs separated by comma', 'licensehub')}
						result={result}
						onChange={(e) => changeFormValue('fluentCRMTags', e.target.value)}
					/>
				</FormGroup>
			</>
		);
	}
};
