/**
 * Add a submit input styled like a button
 *
 * @since 1.0.2
 *
 * @param className
 * @param children
 * @param props
 * @returns {JSX.Element}
 * @constructor
 */
export const Button = ({ className, children, ...props }) => {
	const defaultClasses = "tada-button";

	return (
		<button
			className={className ? defaultClasses + " " + className : defaultClasses}
			{...props}
		>
			{children}
		</button>
	);
};
