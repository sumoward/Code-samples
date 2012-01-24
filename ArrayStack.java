//Brian Ward
//98110098
public class ArrayStack implements Stack {

	public int capacity;
	public int t;
	public Object A[];

	public ArrayStack(int i) {
		// set the intial size
		capacity = i;
		// create an array of that size
		A = new Object[capacity];
		t = -1;
	}

	// getter for t
	public int getT() {
		return t;
	}

	@Override
	public boolean isEmpty() {

		return t < 0;
	}

	@Override
	public Object pop() throws ListEmptyException {

		if (A[t] == null)
			throw new ListEmptyException();
		// set e to the top of the stack
		Object e = A[t];
		// Set the top to Null
		A[t] = null;
		// decrement t
		t = t - 1;
		// return the popped element
		return e;
	}

	@Override
	public void push(Object element) throws BoundaryViolationException {

		if (t == capacity)
			throw new BoundaryViolationException();

		A[t + 1] = element;
		// increment t
		t = t + 1;

	}

	@Override
	public int size() {
		return t + 1;
	}

	@Override
	public Object top() {
		// return the top object
		return A[t];
	}

	// a method to print out to a string
	public String toString() {
		// print t
		System.out.print((getT() + 1));
		System.out.print("    ");
		// open the brackets
		// System.out.print("{ ");
		// loop through the results
		for (int i = 0; i < capacity - 1; i++) {
			// if the element is null dont print it
			if (A[i] != null) {
				System.out.print((String) A[i]);
				// check to see if a xomma should be printed
				if (A[i + 1] != null) {
					System.out.print(" ");
				}
			}// System.out.println("hello************************************************");
		}
		// print out the last element
		if (A[capacity - 1] != null) {
			System.out.println(A[capacity - 1]);
		} else {
			System.out.println(" ");
		}
		return null;
	}
}
