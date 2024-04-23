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
	const defaultClasses = "tada-form-group";

	return (
		<div
			className={className ? defaultClasses + " " + className : defaultClasses}
			{...props}
		>
			{children}
		</div>
	);
};
