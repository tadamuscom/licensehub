import '@global/css/main.css';

// Constants
export * from '@global/constants';

// Form
export * from '@global/components/form/Button';
export * from '@global/components/form/CheckBox';
export * from '@global/components/form/FormGroup';
export * from '@global/components/form/HelperText';
export * from '@global/components/form/Label';
export * from '@global/components/form/Select';
export * from '@global/components/form/SelectOption';
export * from '@global/components/form/Input';
export * from '@global/components/form/ErrorMessage';
export * from '@global/components/form/SuccessMessage';
export * from '@global/components/form/FormStatus';
export * from '@global/components/form/Textarea';

// Layout
export * from '@global/components/layout/Header';

// Table
export * from '@global/components/table/Table';
export * from '@global/components/table/TableRow';
export * from '@global/components/table/TableColumn';
export * from '@global/components/table/TableHeader';

// Typography
export * from '@global/components/typography/HeadingTwo';
export * from '@global/components/typography/HeadingThree';

// LinkButton
export * from '@global/components/LinkButton';

// Hooks
export * from '@global/hooks/useForms';
export * from '@global/hooks/useTables';

export const addQueryParameter = (key, value) => {
	let url = new URL(window.location.href);
	let searchParams = new URLSearchParams(url.search);

	searchParams.set(key, value);
	url.search = searchParams.toString();

	window.history.pushState({}, '', url.toString());
};

export const removeQueryParameter = (key) => {
	let url = new URL(window.location.href);
	let searchParams = new URLSearchParams(url.search);

	searchParams.delete(key);
	url.search = searchParams.toString();

	window.history.pushState({}, '', url.toString());
};

export const getQueryParameter = (key) => {
	let searchParams = new URLSearchParams(window.location.search);
	return searchParams.get(key);
};
