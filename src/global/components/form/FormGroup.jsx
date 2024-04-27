/**
 * Add a div that will be the wrapper for input elements, their labels and helper texts.
 *
 * @since 1.0.2
 *
 * @param className
 * @param children
 * @param props
 * @returns {JSX.Element}
 * @constructor
 */
export const FormGroup = ({ className, children, ...props }) => {
	const defaultClasses = 'my-4';

	return (
		<div
			className={className ? className + ' ' + defaultClasses : defaultClasses}
			{...props}>
			{children}
		</div>
	);
};
