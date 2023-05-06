interface ClassStudentsProps {
  students: {
    id: string;
    forename: string;
    surname: string;
  }[];
}

function ClassStudents({ students }: ClassStudentsProps) {
  return (
    <div>
      <h3>Students:</h3>
      <ul>
        {students.map((student) => (
          <li key={student.id}>
            {student.forename} {student.surname}
          </li>
        ))}
      </ul>
    </div>
  );
}

export default ClassStudents;
