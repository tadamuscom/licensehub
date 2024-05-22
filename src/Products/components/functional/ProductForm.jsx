import { __ } from '@wordpress/i18n';
import { FormGroup, Label, Input, Button, FormStatus } from '@global';

export const ProductForm = ({
	formData,
	result,
	changeFormValue,
	loading,
	handleSubmit,
	disabled,
}) => {
	return (
		<form onSubmit={handleSubmit}>
			<FormGroup>
				<Label htmlFor="lchb-name">{__('Name', 'licensehub')}</Label>
				<Input
					type="text"
					id="lchb-name"
					name="lchb-name"
					value={formData.name}
					result={result}
					onChange={(e) => changeFormValue('name', e.target.value)}
					disabled={disabled}
					autoFocus={true}
				/>
			</FormGroup>
			<FormGroup extraClass="tada-form-submit">
				<Button type="submit" loading={loading} disabled={disabled}>
					{loading
						? __('Loading...', 'licensehub')
						: __('Save Product', 'licensehub')}
				</Button>
				<FormStatus status={result} />
			</FormGroup>
		</form>
	);
};
