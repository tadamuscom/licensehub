import { ErrorMessage } from '@global/components/form/ErrorMessage';
import { SuccessMessage } from '@global/components/form/SuccessMessage';

export const FormStatus = ({ status }) => {
	if (status.field) return null;

	if (status.type === 'error') {
		return <ErrorMessage inline={true}>{status.message}</ErrorMessage>;
	}

	if (status.type === 'success') {
		return <SuccessMessage inline={true}>{status.message}</SuccessMessage>;
	}

	return null;
};
